<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Mobil;
use App\Models\Pembayaran;
use App\Models\PencairanKomisi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PendapatanKomisiController extends Controller
{
    public function index()
    {
        $id_mitra = Auth::id();

        // 1. Ambil daftar mobil milik mitra beserta riwayat booking yang pembayarannya LUNAS
        $mobils = Mobil::with(['brand', 'kategori', 'bookings' => function ($q) {
            $q->whereHas('pembayaran', function ($query) {
                $query->whereIn('status_pembayaran', ['dibayar', 'lunas', 'selesai']);
            })->with(['pembayaran' => function ($query) {
                $query->whereIn('status_pembayaran', ['dibayar', 'lunas', 'selesai']);
            }])->orderBy('tanggal_mulai', 'desc');
        }])
            ->where('id_pemilik_mobil', $id_mitra)
            ->get();

        // 2. Kalkulasi Pendapatan Global & Per Mobil
        $totalPendapatanGlobal = 0; // Disesuaikan dengan view
        foreach ($mobils as $mobil) {
            $pendapatanMobil = 0;
            foreach ($mobil->bookings as $booking) {
                if ($booking->pembayaran) {
                    $pendapatanMobil += $booking->pembayaran->komisi_pemilik;
                }
            }
            $mobil->total_pendapatan = $pendapatanMobil;
            $totalPendapatanGlobal += $pendapatanMobil; // Disesuaikan dengan view
        }

        // 3. Ambil Riwayat Pencairan Dana dari Admin
        $riwayatPencairan = PencairanKomisi::where('id_pemilik_mobil', $id_mitra)
            ->orderBy('created_at', 'desc')
            ->get();

        // Disesuaikan dengan view (hanya yang berstatus 'disetujui' yang mengurangi saldo berjalan)
        $totalDicairkanGlobal = $riwayatPencairan->where('status', 'disetujui')->sum('jumlah');
        $tunggakanAdminGlobal = $totalPendapatanGlobal - $totalDicairkanGlobal;

        $mitraProfile = \DB::table('pemilik_mobil')->where('id_user', $id_mitra)->first();

        // Path folder tetap menggunakan mitra.komisi.index
        return view('mitra.komisi.index', compact(
            'mobils', 'riwayatPencairan', 'totalPendapatanGlobal', 'totalDicairkanGlobal', 'tunggakanAdminGlobal', 'mitraProfile'
        ));
    }

    // NAMA FUNGSI DIUBAH MENJADI 'detail' AGAR COCOK DENGAN ROUTE
    public function detail($id)
    {
        $id_mitra = Auth::id();

        // Ambil data mobil spesifik milik mitra ini
        $mobil = Mobil::with(['brand', 'kategori'])->where('id_pemilik_mobil', $id_mitra)->findOrFail($id);

        // Ambil seluruh transaksi pesanan lunas khusus untuk mobil ini
        $bookings = Booking::where('id_mobil', $mobil->id)
            ->whereHas('pembayaran', function ($query) {
                $query->whereIn('status_pembayaran', ['dibayar', 'lunas', 'selesai']);
            })
            ->with('pembayaran')
            ->orderBy('id', 'desc')
            ->get();

        // Hitung total pendapatan khusus mobil ini
        $totalKomisiMobil = $bookings->sum(function ($b) {
            return $b->pembayaran->komisi_pemilik;
        });

        // Hitung status tunggakan global mitra untuk ditampilkan sebagai alert warning
        $allMobils = Mobil::where('id_pemilik_mobil', $id_mitra)->get();
        $totalPendapatanGlobal = 0;
        foreach ($allMobils as $m) {
            $totalPendapatanGlobal += Pembayaran::whereHas('booking', function ($q) use ($m) {
                $q->where('id_mobil', $m->id);
            })->whereIn('status_pembayaran', ['dibayar', 'lunas', 'selesai'])->sum('komisi_pemilik');
        }

        $totalDicairkanGlobal = PencairanKomisi::where('id_pemilik_mobil', $id_mitra)->where('status', 'disetujui')->sum('jumlah');
        $tunggakanAdminGlobal = $totalPendapatanGlobal - $totalDicairkanGlobal;

        // Path folder tetap menggunakan mitra.komisi.detail
        return view('mitra.komisi.detail', compact('mobil', 'bookings', 'totalKomisiMobil', 'tunggakanAdminGlobal'));
    }

    public function ajukanPencairan(Request $request)
    {
        $id_mitra = Auth::id();

        $request->validate([
            'jumlah' => 'required|numeric|min:10000',
            'nama_bank' => 'required|string|max:100',
            'nomor_rekening' => 'required|string|max:50',
            'nama_rekening' => 'required|string|max:150',
            'catatan' => 'nullable|string|max:255',
        ]);

        // Hitung total pendapatan global
        $mobils = Mobil::where('id_pemilik_mobil', $id_mitra)->get();
        $totalPendapatanGlobal = 0;
        foreach ($mobils as $mobil) {
            $pendapatanMobil = Pembayaran::whereHas('booking', function ($q) use ($mobil) {
                $q->where('id_mobil', $mobil->id);
            })->whereIn('status_pembayaran', ['dibayar', 'lunas', 'selesai'])->sum('komisi_pemilik');
            $totalPendapatanGlobal += $pendapatanMobil;
        }

        $totalDicairkanGlobal = PencairanKomisi::where('id_pemilik_mobil', $id_mitra)->where('status', 'disetujui')->sum('jumlah');
        $saldoBerjalan = $totalPendapatanGlobal - $totalDicairkanGlobal;

        if ($request->jumlah > $saldoBerjalan) {
            return back()->with('error', 'Nominal pengajuan pencairan melebihi saldo berjalan Anda saat ini (Rp ' . number_format($saldoBerjalan, 0, ',', '.') . ').');
        }

        PencairanKomisi::create([
            'id_pemilik_mobil' => $id_mitra,
            'jumlah' => $request->jumlah,
            'status' => 'pending',
            'nama_bank' => $request->nama_bank,
            'nomor_rekening' => $request->nomor_rekening,
            'nama_rekening' => $request->nama_rekening,
            'catatan' => $request->catatan,
        ]);

        // Update bank info di profil pemilik_mobil mitra
        \DB::table('pemilik_mobil')
            ->where('id_user', $id_mitra)
            ->update([
                'nama_bank' => $request->nama_bank,
                'nomor_rekening' => $request->nomor_rekening,
            ]);

        return back()->with('success', 'Pengajuan pencairan dana berhasil dikirim dan sedang menunggu persetujuan admin.');
    }
}
