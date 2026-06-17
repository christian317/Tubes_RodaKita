<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatBookingController extends Controller
{
    public function index(Request $request)
    {
        // PERBAIKAN: Tambahkan 'ulasanMobil' di dalam array with()
        $query = Booking::with(['mobil.brand', 'pembayaran', 'ulasanMobil'])
            ->where('id_user', Auth::id())
            ->orderBy('id', 'desc');

        // Logika Filter Tab ala Shopee
        if ($request->filled('status') && $request->status !== 'semua') {
            if ($request->status === 'menunggu') {
                $query->whereIn('status', ['menunggu', 'dibayar']);
            } else {
                $query->where('status', $request->status);
            }
        }

        // Pagination 10 data
        $bookings = $query->paginate(10)->appends($request->query());

        return view('pelanggan.riwayatBooking.index', compact('bookings'));
    }

    // FUNGSI BARU: Proses simpan rating dan ulasan pelanggan untuk mobil
    public function simpanUlasanMobil(Request $request, $id)
    {
        // 1. Validasi inputan form
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'catatan' => 'required|string|max:500',
        ]);

        // 2. Pastikan pesanan benar-benar milik user yang sedang login
        $booking = Booking::where('id_user', Auth::id())->findOrFail($id);

        // 3. Validasi keamanan ganda: Hanya status selesai yang bisa diulas
        if ($booking->status !== 'selesai') {
            return back()->with('error', 'Anda hanya bisa memberikan ulasan setelah masa sewa selesai.');
        }

        // 4. Cegah ulasan ganda jika user merefresh halaman (opsional tapi sangat aman)
        if ($booking->ulasanMobil) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk pesanan ini.');
        }

        // 5. Simpan data ke tabel ulasan
        Ulasan::create([
            'id_booking' => $booking->id,
            'tipe' => 'mobil',
            'rating' => $request->rating,
            'catatan' => $request->catatan,
        ]);

        return back()->with('success', 'Terima kasih! Ulasan dan rating Anda untuk mobil ini berhasil disimpan.');
    }
}
