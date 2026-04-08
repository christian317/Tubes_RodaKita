<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mobil;
use App\Models\Booking;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Carbon\Carbon;

class PelangganController extends Controller
{
    public function dashboard()
    {
        $mobils = Mobil::with(['brand', 'kategori'])
            ->where('status_katalog', 1)
            ->where('status_mobil', 'tersedia') // <--- Filter khusus yang 'tersedia'
            ->orderBy('id', 'desc')
            ->get();

        return view('pelanggan.dashboard', compact('mobils'));
    }

    public function detail_mobil($id)
    {
        $mobil = Mobil::with(['brand', 'kategori', 'pemilik'])->findOrFail($id);

        if ($mobil->status_katalog == 0 || $mobil->status_mobil != 'tersedia') {
            return redirect()->route('pelanggan.dashboard')->with('error', 'Mohon maaf, mobil tersebut saat ini sedang di-booked.');
        }

        return view('pelanggan.detail_mobil', compact('mobil'));
    }

    public function checkout($id_mobil)
    {
        $mobil = Mobil::findOrFail($id_mobil);

        if ($mobil->status_mobil != 'tersedia') {
            return redirect()->route('pelanggan.dashboard')
                ->with('error', 'Mobil sedang tidak tersedia atau sudah di-booked.');
        }

        return view('pelanggan.order.checkout', compact('mobil'));
    }

    public function prosesCheckout(Request $request)
    {
        $request->validate([
            'id_mobil' => 'required',
            'waktu_mulai' => 'required|date|after_or_equal:today',
            'waktu_selesai' => 'required|date|after_or_equal:waktu_mulai',
        ]);

        $mobil = Mobil::findOrFail($request->id_mobil);

        // Hitung durasi
        $waktu_mulai = Carbon::parse($request->waktu_mulai);
        $waktu_selesai = Carbon::parse($request->waktu_selesai);
        $jumlah_hari = $waktu_mulai->diffInDays($waktu_selesai);

        if ($jumlah_hari == 0) $jumlah_hari = 1;

        $total_bayar = $jumlah_hari * $mobil->harga_sewa;
        $komisi_pemilik = $total_bayar;

        DB::beginTransaction();
        try {
            // ✅ INSERT BOOKING (SESUAI DATABASE)
            $booking = Booking::create([
                'id_user' => Auth::id(),
                'id_mobil' => $mobil->id,
                'tanggal_mulai' => $waktu_mulai->format('Y-m-d H:i:s'),
                'tanggal_selesai' => $waktu_selesai->format('Y-m-d H:i:s'),
                'waktu_mulai' => $waktu_mulai->format('H:i:s'),
                'waktu_selesai' => $waktu_selesai->format('H:i:s'),
                'status' => 'menunggu',
            ]);

            // ✅ INSERT PEMBAYARAN (SESUAI DATABASE)
            $transaksi = Pembayaran::create([
                'id_booking' => $booking->id,
                'total_pembayaran' => $total_bayar,
                'status_pemabayaran' => 'belum_dibayar', // ikut nama di DB
                'komisi_pemilik' => $komisi_pemilik,
            ]);

            // Update status mobil
            $mobil->update(['status_mobil' => 'booked']);

            DB::commit();

            // ✅ MIDTRANS CONFIG
            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => 'ORDER-' . $transaksi->id, 
                    'gross_amount' => $transaksi->total_pembayaran,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->nama,
                    'email' => Auth::user()->email,
                    'phone' => Auth::user()->no_telepon,
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            return view('pelanggan.order.checkout', compact('mobil', 'booking', 'transaksi', 'snapToken'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
