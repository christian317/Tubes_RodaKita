<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Mobil;
use App\Models\Pembayaran;
use App\Models\Promo;
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
            ->where(function ($query) {
                // Booking pending yang sudah lewat batas bayar dianggap tidak aktif
                $query->where(function ($q) {
                    $q->whereIn('status', ['dibayar', 'disewakan']);
                })->orWhere(function ($q) {
                    $q->whereIn('status', ['menunggu_approval', 'menunggu'])
                        ->where(function ($inner) {
                            $inner->whereNull('bayar_sebelum')
                                ->orWhere('bayar_sebelum', '>', Carbon::now());
                        });
                });
            })
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
            ->where(function ($query) {
                // Hanya booking aktif (bukan yang sudah expired batas bayarnya)
                $query->where(function ($q) {
                    $q->whereIn('status', ['dibayar', 'disewakan']);
                })->orWhere(function ($q) {
                    $q->whereIn('status', ['menunggu_approval', 'menunggu'])
                        ->where(function ($inner) {
                            $inner->whereNull('bayar_sebelum')
                                ->orWhere('bayar_sebelum', '>', Carbon::now());
                        });
                });
            })
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
        $isVerified = Auth::user()->verifikasi && Auth::user()->verifikasi->status === 'verified';

        // VALIDASI INPUT
        $rules = [
            'id_mobil' => 'required',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'tipe_layanan' => 'required|in:lepas_kunci,dengan_supir',
            'applied_id_promo' => 'nullable|exists:promo,id',
        ];

        if (! $isVerified) {
            $rules['foto_ktp'] = 'required_if:tipe_layanan,lepas_kunci|image|mimes:jpeg,png,jpg|max:2048';
        } else {
            $rules['foto_ktp'] = 'nullable|image|mimes:jpeg,png,jpg|max:2048';
        }

        $request->validate($rules);

        if ($request->tipe_layanan === 'lepas_kunci' && ! $isVerified) {
            return back()->with('error', 'Layanan lepas kunci hanya tersedia untuk akun yang sudah terverifikasi. Silakan ajukan verifikasi terlebih dahulu.')->withInput();
        }

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

        // PROSES UPLOAD FOTO KTP JIKA ADA ATAU JIKA AKUN SUDAH TERVERIFIKASI
        $pathKtp = null;
        if ($request->hasFile('foto_ktp')) {
            $pathKtp = $request->file('foto_ktp')->store('ktp_pelanggan', 'public');
        } elseif ($isVerified && $request->tipe_layanan === 'lepas_kunci') {
            $pathKtp = Auth::user()->verifikasi->foto_ktp;
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
            $potongan_harga = 0;
            $id_promo = null;
            if ($request->filled('applied_id_promo')) {
                $promo = Promo::lockForUpdate()->find($request->applied_id_promo);
                if ($promo) {
                    $today = Carbon::today();
                    $isExpired = Carbon::parse($promo->tanggal_kadaluarsa)->lt($today);

                    if (! $isExpired && $promo->kuota > 0 && $total_bayar >= $promo->minimal_transaksi) {
                        $id_promo = $promo->id;
                        if ($promo->tipe_potongan === 'persen') {
                            $potongan_harga = ($promo->nominal_potongan / 100) * $total_bayar;
                        } else {
                            $potongan_harga = $promo->nominal_potongan;
                        }

                        if ($potongan_harga > $total_bayar) {
                            $potongan_harga = $total_bayar;
                        }

                        $promo->decrement('kuota');
                    }
                }
            }

            $total_bayar_akhir = $total_bayar - $potongan_harga;
            $komisi_mitra = $total_bayar_akhir * 0.70;

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
                'bayar_sebelum' => Carbon::now()->addMinutes(30),
            ]);

            $transaksi = Pembayaran::create([
                'id_booking' => $booking->id,
                'id_promo' => $id_promo,
                'total_pembayaran' => $total_bayar_akhir,
                'potongan_harga' => $potongan_harga,
                'status_pembayaran' => 'belum_dibayar',
                'komisi_pemilik' => $komisi_mitra,
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

        // Jika ini adalah test notification dari dashboard Midtrans, kembalikan respon 200 OK langsung
        $order_id_raw = $request->input('order_id');
        if ($order_id_raw && str_starts_with($order_id_raw, 'payment_notif_test')) {
            return response()->json([
                'message' => 'Test notification received successfully. Connection is OK!'
            ], 200);
        }

        $transaction = null;
        $order_id = null;
        $fraud = null;

        try {
            $notif = new Notification;
            $transaction = $notif->transaction_status;
            $order_id = $notif->order_id;
            $fraud = $notif->fraud_status;
        } catch (\Exception $e) {
            // Fallback untuk local/testing environment agar manual simulation (Method B) tetap bisa jalan
            if (app()->environment('local', 'testing')) {
                $transaction = $request->input('transaction_status');
                $order_id = $request->input('order_id');
                $fraud = $request->input('fraud_status');
            } else {
                return response()->json(['message' => 'Notification error: '.$e->getMessage()], 400);
            }
        }

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
