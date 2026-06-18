<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Mobil;
use Illuminate\Support\Facades\Auth;

class MitraMobilController extends Controller
{
    public function dashboard()
    {
        // Ambil semua id mobil milik mitra yang sedang login
        $mobilIds = Mobil::where('id_pemilik_mobil', Auth::id())->pluck('id');

        // Booking aktif (sedang berjalan) untuk mobil milik mitra ini
        $bookingAktif = Booking::with(['mobil.brand', 'user'])
            ->whereIn('id_mobil', $mobilIds)
            ->whereIn('status', ['disewakan', 'dibayar', 'menunggu'])
            ->orderBy('tanggal_mulai', 'desc')
            ->take(5)
            ->get();

        // Statistik cepat
        $totalArmada   = $mobilIds->count();
        $aktifDisewa   = Mobil::whereIn('id', $mobilIds)->where('status_mobil', 'booked')->count();
        $perluPerhatian = Booking::whereIn('id_mobil', $mobilIds)
            ->whereDate('tanggal_selesai', today())
            ->whereIn('status', ['disewakan', 'dibayar'])
            ->count();

        return view('mitra.dashboard', compact('bookingAktif', 'totalArmada', 'aktifDisewa', 'perluPerhatian'));
    }
}
