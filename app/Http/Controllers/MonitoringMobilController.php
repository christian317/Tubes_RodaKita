<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mobil;
use Illuminate\Support\Facades\Auth;

class MonitoringMobilController extends Controller
{
    public function index()
    {
        // 1. Ambil semua mobil milik Mitra yang sedang login
        // Beserta data brand, kategori, dan riwayat booking yang diurutkan dari yang terbaru
        $mobils = Mobil::with(['brand', 'kategori', 'bookings' => function($q) {
            $q->with('user') // Ambil data pelanggan yang menyewa
              ->whereIn('status', ['menunggu', 'dibayar', 'disewakan', 'selesai'])
              ->orderBy('tanggal_mulai', 'desc');
        }])
        ->where('id_pemilik_mobil', Auth::id())
        ->get();

        // 2. Hitung Statistik Cepat untuk Dashboard Atas
        $totalMobil = $mobils->count();
        $sedangDisewa = $mobils->where('status_mobil', 'booked')->count();
        $tersedia = $mobils->where('status_mobil', 'sewa')->count();

        return view('mitra.monitoringMobil.index', compact('mobils', 'totalMobil', 'sedangDisewa', 'tersedia'));
    }
}