<?php

namespace App\Http\Controllers;

use App\Models\Mobil;
use App\Models\Ulasan;
use Illuminate\Support\Facades\Auth;

class UlasanPelangganController extends Controller
{
    public function index()
    {
        $id_mitra = Auth::id();

        // Mengambil daftar mobil milik mitra beserta rata-rata rating dan jumlah ulasannya
        $mobils = Mobil::with(['brand', 'kategori'])
            ->withAvg('ulasans as ulasans_avg_rating', 'rating')
            ->withCount('ulasans')
            ->where('id_pemilik_mobil', $id_mitra)
            ->get();

        return view('mitra.ulasanPelanggan.index', compact('mobils'));
    }

    public function detail($id)
    {
        $id_mitra = Auth::id();

        // Pastikan mobil tersebut benar-benar milik mitra yang sedang login
        $mobil = Mobil::with(['brand', 'kategori'])
            ->withAvg('ulasans as ulasans_avg_rating', 'rating')
            ->withCount('ulasans')
            ->where('id_pemilik_mobil', $id_mitra)
            ->findOrFail($id);

        // Mengambil semua rincian ulasan khusus untuk mobil ini
        $ulasans = $mobil->ulasans()
            ->with('booking.user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mitra.ulasanPelanggan.detail', compact('mobil', 'ulasans'));
    }
}
