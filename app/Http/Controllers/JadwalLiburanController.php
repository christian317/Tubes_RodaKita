<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\JadwalLiburan;
use Illuminate\Support\Facades\Auth;

class JadwalLiburanController extends Controller
{
    // 1. Menampilkan daftar kartu pesanan yang siap dibuat jadwal
    public function index()
    {
        // Hanya ambil pesanan milik user login dengan status: menunggu (bayar/diambil), dibayar, disewakan
        $bookings = Booking::with(['mobil.brand'])
            ->where('id_user', Auth::id())
            ->whereIn('status', ['menunggu', 'dibayar', 'disewakan'])
            ->orderBy('tanggal_mulai', 'asc')
            ->get();

        return view('pelanggan.jadwalLiburan.index', compact('bookings'));
    }

    // 2. Menampilkan halaman form & timeline jadwal untuk 1 pesanan
    public function detail($id_booking)
    {
        $booking = Booking::with('mobil.brand')->where('id_user', Auth::id())->findOrFail($id_booking);

        if ($booking->status == 'menunggu_approval' || $booking->status == 'batal' || $booking->status == 'selesai') {
            return redirect()->route('pelanggan.jadwal.index')->with('error', 'Jadwal liburan tidak tersedia untuk status pesanan ini.');
        }

        // Urutkan berdasarkan tanggal, lalu berdasarkan JAM MULAI
        $jadwals = JadwalLiburan::where('id_booking', $id_booking)
                    ->orderBy('tanggal', 'asc')
                    ->orderBy('jam_mulai', 'asc')
                    ->get()
                    ->groupBy('tanggal');

        return view('pelanggan.jadwalLiburan.detail', compact('booking', 'jadwals'));
    }

    // 3. Menyimpan data jadwal baru
    public function create(Request $request, $id_booking)
    {
        $booking = Booking::findOrFail($id_booking);

        $request->validate([
            'tanggal' => 'required|date|after_or_equal:' . \Carbon\Carbon::parse($booking->tanggal_mulai)->format('Y-m-d') . '|before_or_equal:' . \Carbon\Carbon::parse($booking->tanggal_selesai)->format('Y-m-d'),
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'kegiatan' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255'
        ], [
            'jam_selesai.after' => 'Jam selesai harus lebih dari jam mulai.'
        ]);

        JadwalLiburan::create([
            'id_booking' => $id_booking,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'kegiatan' => $request->kegiatan,
            'lokasi' => $request->lokasi
        ]);

        return back()->with('success', 'Kegiatan berhasil ditambahkan ke jadwal Anda!');
    }

    // 4. Menghapus jadwal
    public function destroy($id)
    {
        $jadwal = JadwalLiburan::findOrFail($id);
        
        // Pastikan hanya pemilik pesanan yang bisa menghapus
        $booking = Booking::where('id_user', Auth::id())->findOrFail($jadwal->id_booking);
        
        $jadwal->delete();
        return back()->with('success', 'Kegiatan berhasil dihapus.');
    }
}