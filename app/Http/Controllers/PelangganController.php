<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Mobil;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;

class PelangganController extends Controller
{
    public function dashboard()
    {
        $mobils = Mobil::with(['brand', 'kategori'])
            ->withAvg('ulasans as ulasans_avg_rating', 'rating')
            ->withCount('ulasans')
            ->where('status_katalog', 1)
            ->get();

        return view('pelanggan.dashboard', compact('mobils'));
    }

    private function isCarAvailable($id_mobil, $tanggal_mulai, $tanggal_selesai): bool
    {
        return ! Booking::where('id_mobil', $id_mobil)
            ->whereIn('status', ['menunggu_approval', 'menunggu', 'dibayar', 'disewakan'])
            ->where(function ($query) use ($tanggal_mulai, $tanggal_selesai) {
                $query->where(function ($q) use ($tanggal_mulai, $tanggal_selesai) {
                    $q->whereDate('tanggal_mulai', '<=', $tanggal_selesai)
                        ->whereDate('tanggal_selesai', '>=', $tanggal_mulai);
                });
            })->exists();
    }

    public function detail_mobil($id)
    {
        $mobil = Mobil::with(['brand', 'kategori', 'ulasans.booking.user'])->findOrFail($id);

        if ($mobil->status_katalog == 0) {
            return redirect()->route('pelanggan.dashboard')->with('error', 'Mobil sedang tidak tersedia di katalog.');
        }

        $bookedDates = Booking::where('id_mobil', $mobil->id)
            ->whereIn('status', ['menunggu_approval', 'menunggu', 'dibayar', 'disewakan'])
            ->where('tanggal_selesai', '>=', Carbon::now()->format('Y-m-d 00:00:00'))
            ->get(['tanggal_mulai', 'tanggal_selesai']);

        $disabledDates = [];
        foreach ($bookedDates as $booking) {
            $disabledDates[] = [
                'from' => Carbon::parse($booking->tanggal_mulai)->format('Y-m-d'),
                'to' => Carbon::parse($booking->tanggal_selesai)->format('Y-m-d'),
            ];
        }

        return view('pelanggan.detail_mobil', compact('mobil', 'disabledDates'));
    }

    public function checkout(Request $request, $id_mobil)
    {
        $mobil = Mobil::findOrFail($id_mobil);

        if (! $request->filled('rentang_tanggal')) {
            return redirect()->route('pelanggan.mobil.detail_mobil', $mobil->id)->with('error', 'Silakan pilih tanggal penyewaan melalui kalender terlebih dahulu.');
        }

        $rentang_tanggal = str_replace(' to ', ' - ', $request->rentang_tanggal);
        $dates = explode(' - ', $rentang_tanggal);

        $tgl_mulai = trim($dates[0]) ?? null;
        $tgl_selesai = trim($dates[1] ?? $tgl_mulai);

        return view('pelanggan.order.checkout', compact('mobil', 'tgl_mulai', 'tgl_selesai'));
    }

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

        $waktu_mulai_full = Carbon::parse($request->tanggal_mulai.' '.$request->waktu_mulai);
        $waktu_selesai_full = Carbon::parse($request->tanggal_selesai.' '.$request->waktu_selesai);

        if ($waktu_selesai_full->lte($waktu_mulai_full)) {
            return back()->withErrors(['waktu_selesai' => 'Waktu pengembalian tidak valid.'])->withInput();
        }

        $selisih_jam = $waktu_mulai_full->diffInHours($waktu_selesai_full);
        $jumlah_hari = ceil($selisih_jam / 24);
        if ($jumlah_hari <= 0) {
            $jumlah_hari = 1;
        }

        $biaya_sewa_mobil = $jumlah_hari * $mobil->harga_sewa;
        $biaya_supir = ($request->tipe_layanan == 'dengan_supir') ? (150000 * $jumlah_hari) : 0;
        $total_bayar = $biaya_sewa_mobil + $biaya_supir;

        // LOGIKA PEMBAGIAN KOMISI (70% Mitra, 30% Perusahaan)
        $komisi_mitra = $total_bayar * 0.70;

        // PROSES UPLOAD FOTO KTP JIKA ADA
        $pathKtp = null;
        if ($request->hasFile('foto_ktp')) {
            $pathKtp = $request->file('foto_ktp')->store('ktp_pelanggan', 'public');
        }

        $status_booking = 'menunggu_approval';

        if ($request->tipe_layanan == 'lepas_kunci') {
            $verifikasi = Auth::user()->verifikasi;
            if (! $verifikasi || $verifikasi->status !== 'verified') {
                return back()->with('error', 'Layanan Lepas Kunci memerlukan verifikasi KTP/SIM terlebih dahulu. Silakan verifikasi akun Anda.')->withInput();
            }
        }

        if (! $this->isCarAvailable($mobil->id, $request->tanggal_mulai, $request->tanggal_selesai)) {
            return back()->with('error', 'Mobil sudah dibooking oleh pengguna lain pada rentang tanggal tersebut.')->withInput();
        }

        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'id_user' => Auth::id(),
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
                'komisi_pemilik' => $komisi_mitra, // Menyimpan 70% ke dalam database otomatis
            ]);

            DB::commit();

            // PROSES SNAP MIDTRANS
            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => 'ORDER-'.$transaksi->id.'-'.time(),
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

            return back()->with('error', 'Terjadi kesalahan sistem: '.$e->getMessage())->withInput();
        }
    }

    public function handleNotification(Request $request)
    {
        // Inisialisasi konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

        try {
            $notif = new Notification;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Notification error: '.$e->getMessage()], 400);
        }

        $transaction = $notif->transaction_status;
        $order_id = $notif->order_id;
        $fraud = $notif->fraud_status;

        // Memecah order_id dari format: ORDER-{id_pembayaran}-{timestamp}
        $parts = explode('-', $order_id);
        $pembayaranId = $parts[1] ?? null;

        if (! $pembayaranId) {
            return response()->json(['message' => 'Format Order ID tidak valid'], 400);
        }

        // Cari data transaksi berdasarkan ID Pembayaran hasil pemecahan string
        $pembayaran = Pembayaran::find($pembayaranId);
        if (! $pembayaran) {
            return response()->json(['message' => 'Data pembayaran tidak ditemukan'], 444);
        }

        $booking = Booking::find($pembayaran->id_booking);

        // LOGIKA PENENTUAN STATUS PEMBAYARAN AUTOMATIC
        if ($transaction == 'capture') {
            if ($fraud == 'challenge') {
                $pembayaran->update(['status_pembayaran' => 'pending']);
            } elseif ($fraud == 'accept') {
                $pembayaran->update(['status_pembayaran' => 'dibayar']);
                if ($booking) {
                    $booking->update(['status' => 'dibayar']);
                }
            }
        } elseif ($transaction == 'settlement') {
            // TRANSAKSI LUNAS BERHASIL
            $pembayaran->update(['status_pembayaran' => 'dibayar']);

            // Ubah status booking pelanggan agar admin tahu dana sudah masuk di sistem
            if ($booking) {
                $booking->update(['status' => 'dibayar']);
            }
        } elseif ($transaction == 'pending') {
            $pembayaran->update(['status_pembayaran' => 'pending']);
        } elseif ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
            // TRANSAKSI GAGAL ATAU KADALUARSA
            $pembayaran->update(['status_pembayaran' => 'gagal']);
            if ($booking) {
                $booking->update(['status' => 'batal']);
                // Kembalikan ketersediaan status mobil ke sedia sewa kembali
                if ($booking->mobil) {
                    $booking->mobil->update(['status_mobil' => 'sewa']);
                }
            }
        }

        return response()->json(['message' => 'Callback berhasil diproses aplikasi Roda Kita']);
    }
}
