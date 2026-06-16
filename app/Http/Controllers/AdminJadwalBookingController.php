<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Mobil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminJadwalBookingController extends Controller
{
    public function index()
    {
        // 1. DATA UNTUK TABEL (Ambil semua booking beserta relasinya)
        $bookings = Booking::with(['mobil.brand', 'pembayaran', 'user'])->orderBy('id', 'desc')->get();

        // 2. DATA UNTUK KALENDER (Hanya ambil yang SUDAH di-ACC admin / berjalan)
        // Status yang masuk: menunggu (bayar), dibayar (lunas), berjalan
        $approvedBookings = Booking::with(['mobil.brand', 'user'])
            ->whereIn('status', ['menunggu', 'dibayar', 'berjalan'])
            ->get();

        $events = [];
        foreach ($approvedBookings as $b) {
            // Tentukan warna kalender
            $color = '#0d6efd'; // Biru: Jadwal Fix (Dibayar/Berjalan)
            if ($b->status == 'menunggu') {
                $color = '#6c757d'; // Abu-abu: Di-ACC tapi belum dibayar
            }

            // FullCalendar bersifat eksklusif pada hari terakhir, jadi kita tambah 1 hari
            $endDate = Carbon::parse($b->tanggal_selesai)->addDay()->format('Y-m-d');

            $events[] = [
                'id' => $b->id,
                'title' => ($b->mobil->plat_nomer ?? '') . ' | ' . ($b->user->nama ?? 'Sistem'),
                'start' => Carbon::parse($b->tanggal_mulai)->format('Y-m-d'),
                'end' => $endDate,
                'color' => $color,
                'extendedProps' => [
                    'mobil' => ($b->mobil->brand->nama_brand ?? '') . ' ' . ($b->mobil->model ?? ''),
                    'layanan' => ucwords(str_replace('_', ' ', $b->tipe_layanan)),
                    'status' => $b->status,
                    'tgl_teks' => Carbon::parse($b->tanggal_mulai)->format('d M Y') . ' s/d ' . Carbon::parse($b->tanggal_selesai)->format('d M Y')
                ]
            ];
        }

        // Kirim $bookings dan $events ke tampilan
        return view('admin.booking.index', compact('bookings', 'events'));
    }

    public function approve($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status !== 'menunggu_approval') {
            return back()->with('error', 'Status pesanan tidak valid untuk disetujui.');
        }

        // Ubah status menjadi menunggu pembayaran
        $booking->update(['status' => 'menunggu']);

        return back()->with('success', 'Pengajuan Lepas Kunci disetujui. Jadwal mobil telah masuk ke kalender.');
    }

    public function reject($id)
    {
        $booking = Booking::findOrFail($id);

        DB::beginTransaction();
        try {
            $booking->update(['status' => 'batal']);
            
            $mobil = Mobil::find($booking->id_mobil);
            if ($mobil) {
                $mobil->update(['status_mobil' => 'sewa']);
            }

            if ($booking->pembayaran) {
                $booking->pembayaran->update(['status_pembayaran' => 'dibatalkan']);
            }

            DB::commit();
            return back()->with('success', 'Pengajuan sewa ditolak dan dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        // Validasi disesuaikan dengan skenario baru
        $request->validate(['status' => 'required|in:disewakan,selesai']);
        
        $booking = Booking::findOrFail($id);
        
        DB::beginTransaction();
        try {
            $booking->update(['status' => $request->status]);

            // Jika pelanggan sudah mengembalikan mobil (Selesai)
            if ($request->status == 'selesai') {
                $mobil = Mobil::find($booking->id_mobil);
                if ($mobil) {
                    $mobil->update(['status_mobil' => 'sewa']); // Mobil tersedia kembali
                }
            }

            DB::commit();
            return back()->with('success', 'Status pesanan berhasil diperbarui menjadi ' . ucfirst($request->status) . '.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }
}