<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Mobil;
use App\Models\KondisiMobil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminJadwalBookingController extends Controller
{
    public function index(Request $request)
    {
        $totalPesanan = Booking::count();
        $perluPersetujuan = Booking::where('status', 'menunggu_approval')->count();
        $aktifDisewakan = Booking::whereIn('status', ['menunggu', 'dibayar', 'disewakan'])->count();
        $telahSelesai = Booking::where('status', 'selesai')->count();

        // Mengambil relasi yang sudah diperbaiki
        $query = Booking::with(['mobil.brand', 'pembayaran', 'user', 'kondisiPengambilan', 'kondisiPengembalian', 'ulasanPelanggan', 'ulasanMobil'])->orderBy('id', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('nama', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('mobil', function($m) use ($search) {
                      $m->where('model', 'like', '%' . $search . '%')
                        ->orWhere('plat_nomer', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($request->filled('status') && $request->status !== 'semua') {
            if ($request->status === 'menunggu') {
                $query->whereIn('status', ['menunggu', 'dibayar']);
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('tanggal')) { $query->whereDate('tanggal_mulai', $request->tanggal); }
        if ($request->filled('bulan')) { $query->whereMonth('tanggal_mulai', $request->bulan); }
        if ($request->filled('tahun')) { $query->whereYear('tanggal_mulai', $request->tahun); }

        $bookings = $query->paginate(10)->appends($request->query());

        return view('admin.booking.index', compact(
            'bookings', 'totalPesanan', 'perluPersetujuan', 'aktifDisewakan', 'telahSelesai'
        ));
    }

    public function approve($id)
    {
        $booking = Booking::findOrFail($id);
        if ($booking->status !== 'menunggu_approval') {
            return back()->with('error', 'Status pesanan tidak valid untuk disetujui.');
        }
        $booking->update(['status' => 'menunggu']);
        return back()->with('success', 'Pengajuan sewa disetujui.');
    }

    public function reject($id)
    {
        $booking = Booking::findOrFail($id);
        DB::beginTransaction();
        try {
            $booking->update(['status' => 'batal']);
            $mobil = Mobil::find($booking->id_mobil);
            if ($mobil) { $mobil->update(['status_mobil' => 'sewa']); }
            if ($booking->pembayaran) { $booking->pembayaran->update(['status_pembayaran' => 'dibatalkan']); }
            DB::commit();
            return back()->with('success', 'Pengajuan sewa ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan: ' . $e->getMessage());
        }
    }

    // FASE 1: SERAH KUNCI DAN MOBIL (PENGAMBILAN)
    public function serahkanMobil(Request $request, $id)
    {
        $request->validate([
            'odometer' => 'required|integer|min:0',
            'indikator_bensin' => 'required|string|max:45',
            'kondisi_eksterior' => 'required|string',
            'kondisi_interior' => 'required|string',
            'catatan' => 'nullable|string',
            'foto' => 'required|array|min:1', // Validasi file jamak
            'foto.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $booking = Booking::findOrFail($id);

        DB::beginTransaction();
        try {
            // Upload Multiple Images
            $uploadedPaths = [];
            if ($request->hasFile('foto')) {
                foreach ($request->file('foto') as $file) {
                    $uploadedPaths[] = $file->store('kondisi_keluar', 'public');
                }
            }

            KondisiMobil::create([
                'id_booking' => $booking->id,
                'tipe' => 'pengambilan',
                'odometer' => $request->odometer,
                'indikator_bensin' => $request->indikator_bensin,
                'kondisi_eksterior' => $request->kondisi_eksterior,
                'kondisi_interior' => $request->kondisi_interior,
                'denda' => 0,
                'catatan' => $request->catatan,
                'foto_kendaraan' => $uploadedPaths, // Array akan otomatis jadi JSON oleh Laravel Casting
            ]);

            $booking->update(['status' => 'disewakan']);

            DB::commit();
            return back()->with('with_tab_status', 'disewakan')->with('success', 'Kondisi awal berhasil dicatat. Mobil resmi diserahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses penyerahan: ' . $e->getMessage());
        }
    }

    public function terimaMobil(Request $request, $id)
    {
        $request->validate([
            'odometer' => 'required|integer|min:0',
            'indikator_bensin' => 'required|string|max:45',
            'kondisi_eksterior' => 'required|string',
            'kondisi_interior' => 'required|string',
            'denda' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string',
            'foto' => 'required|array|min:1',
            'foto.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            // VALIDASI ULASAN PELANGGAN OLEH ADMIN
            'rating_pelanggan' => 'required|integer|min:1|max:5',
            'catatan_pelanggan' => 'nullable|string'
        ]);

        $booking = Booking::findOrFail($id);

        DB::beginTransaction();
        try {
            // Upload Foto & Catat Kondisi
            $uploadedPaths = [];
            if ($request->hasFile('foto')) {
                foreach ($request->file('foto') as $file) {
                    $uploadedPaths[] = $file->store('kondisi_masuk', 'public');
                }
            }

            KondisiMobil::create([
                'id_booking' => $booking->id,
                'tipe' => 'pengembalian',
                'odometer' => $request->odometer,
                'indikator_bensin' => $request->indikator_bensin,
                'kondisi_eksterior' => $request->kondisi_eksterior,
                'kondisi_interior' => $request->kondisi_interior,
                'denda' => $request->denda ?? 0,
                'catatan' => $request->catatan,
                'foto_kendaraan' => $uploadedPaths,
            ]);

            // SIMPAN ULASAN PELANGGAN
            \App\Models\Ulasan::create([
                'id_booking' => $booking->id,
                'tipe' => 'pelanggan',
                'rating' => $request->rating_pelanggan,
                'catatan' => $request->catatan_pelanggan
            ]);

            $booking->update(['status' => 'selesai']);
            $mobil = Mobil::find($booking->id_mobil);
            if ($mobil) { $mobil->update(['status_mobil' => 'sewa']); }

            DB::commit();
            return back()->with('with_tab_status', 'selesai')->with('success', 'Kondisi akhir & ulasan berhasil dicatat. Rental Selesai.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }
}