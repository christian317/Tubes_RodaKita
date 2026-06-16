<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mobil;
use App\Models\Booking;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Carbon\Carbon;

class PelangganController extends Controller
{
    public function dashboard()
    {
        $mobils = Mobil::with(['brand', 'kategori'])
            ->where('status_katalog', 1)
            ->orderBy('id', 'desc')
            ->get();

        return view('pelanggan.dashboard', compact('mobils'));
    }

public function detail_mobil($id)
    {
        $mobil = Mobil::with(['brand', 'kategori', 'pemilik'])->findOrFail($id);

        if ($mobil->status_katalog == 0) {
            return redirect()->route('pelanggan.dashboard')->with('error', 'Mobil sedang tidak tersedia di katalog.');
        }

        // PERBAIKAN BUG KALENDER: Masukkan 'menunggu_approval' agar mobil yang sedang ditinjau juga terkunci!
        $bookedDates = \App\Models\Booking::where('id_mobil', $mobil->id)
            ->whereIn('status', ['menunggu_approval', 'menunggu', 'dibayar', 'disewakan'])
            ->where('tanggal_selesai', '>=', \Carbon\Carbon::now()->format('Y-m-d 00:00:00'))
            ->get(['tanggal_mulai', 'tanggal_selesai']);

        $disabledDates = [];
        foreach ($bookedDates as $booking) {
            $disabledDates[] = [
                'from' => \Carbon\Carbon::parse($booking->tanggal_mulai)->format('Y-m-d'),
                'to' => \Carbon\Carbon::parse($booking->tanggal_selesai)->format('Y-m-d')
            ];
        }

        return view('pelanggan.detail_mobil', compact('mobil', 'disabledDates'));
    }

    public function checkout(Request $request, $id_mobil)
    {
        $mobil = Mobil::findOrFail($id_mobil);

        if (!$request->filled('rentang_tanggal')) {
            return redirect()->route('pelanggan.mobil.detail_mobil', $mobil->id)->with('error', 'Silakan pilih tanggal penyewaan melalui kalender terlebih dahulu.');
        }

        $rentang_tanggal = str_replace(' to ', ' - ', $request->rentang_tanggal);
        $dates = explode(' - ', $rentang_tanggal);
        
        $tgl_mulai = trim($dates[0]) ?? null;
        $tgl_selesai = trim($dates[1] ?? $tgl_mulai); 

        return view('pelanggan.order.checkout', compact('mobil', 'tgl_mulai', 'tgl_selesai'));
    }

     // ... (fungsi checkout tetap sama) ...

    public function prosesCheckout(Request $request)
    {
        // VALIDASI INPUT
        $request->validate([
            'id_mobil' => 'required',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required', 
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'tipe_layanan' => 'required|in:lepas_kunci,dengan_supir',
            'foto_ktp' => 'required_if:tipe_layanan,lepas_kunci|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $mobil = Mobil::findOrFail($request->id_mobil);

        $waktu_mulai_full = \Carbon\Carbon::parse($request->tanggal_mulai . ' ' . $request->waktu_mulai);
        $waktu_selesai_full = \Carbon\Carbon::parse($request->tanggal_selesai . ' ' . $request->waktu_selesai);

        if ($waktu_selesai_full->lte($waktu_mulai_full)) {
            return back()->withErrors(['waktu_selesai' => 'Waktu pengembalian tidak valid.'])->withInput();
        }

        $selisih_jam = $waktu_mulai_full->diffInHours($waktu_selesai_full);
        $jumlah_hari = ceil($selisih_jam / 24);
        if ($jumlah_hari <= 0) $jumlah_hari = 1;

        $biaya_sewa_mobil = $jumlah_hari * $mobil->harga_sewa;
        $biaya_supir = ($request->tipe_layanan == 'dengan_supir') ? (150000 * $jumlah_hari) : 0;
        $total_bayar = $biaya_sewa_mobil + $biaya_supir;

        // PROSES UPLOAD FOTO KTP JIKA ADA
        $pathKtp = null;
        if ($request->hasFile('foto_ktp')) {
            $pathKtp = $request->file('foto_ktp')->store('ktp_pelanggan', 'public');
        }

        // UNIFORM STATUS: Kedua jenis layanan wajib berstatus 'menunggu_approval' setelah checkout
        $status_booking = 'menunggu_approval';

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $booking = Booking::create([
                'id_user' => \Illuminate\Support\Facades\Auth::id(),
                'id_mobil' => $mobil->id,
                'tipe_layanan' => $request->tipe_layanan,
                'foto_ktp' => $pathKtp, 
                'tanggal_mulai' => $waktu_mulai_full->format('Y-m-d H:i:s'),
                'tanggal_selesai' => $waktu_selesai_full->format('Y-m-d H:i:s'),
                'waktu_mulai' => $request->waktu_mulai, 
                'waktu_selesai' => $request->waktu_selesai, 
                'status' => $status_booking, 
            ]);

            $transaksi = Pembayaran::create([
                'id_booking' => $booking->id,
                'total_pembayaran' => $total_bayar,
                'status_pembayaran' => 'belum_dibayar', 
                'komisi_pemilik' => $total_bayar,
            ]);

            $mobil->update(['status_mobil' => 'booked']);

            \Illuminate\Support\Facades\DB::commit();

            // KEDUA LAYANAN SEKARANG LANGSUNG PROSES SNAP MIDTRANS DI SINI
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => 'ORDER-' . $transaksi->id . '-' . time(), 
                    'gross_amount' => $transaksi->total_pembayaran,
                ],
                'customer_details' => [
                    'first_name' => \Illuminate\Support\Facades\Auth::user()->nama,
                    'email' => \Illuminate\Support\Facades\Auth::user()->email,
                    'phone' => \Illuminate\Support\Facades\Auth::user()->no_telepon,
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Tampilkan halaman yang berisi tombol bayar Midtrans untuk kedua opsi
            return view('pelanggan.order.checkout', compact('mobil', 'booking', 'transaksi', 'snapToken'));

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())->withInput();
        }
    }
}