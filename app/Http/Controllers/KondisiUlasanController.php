<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class KondisiUlasanController extends Controller
{
    // HALAMAN RIWAYAT KONDISI & ULASAN (Hanya Status Selesai)
    public function index(Request $request)
    {
        // PERBAIKAN: Ganti orderBy('updated_at', 'desc') menjadi orderBy('id', 'desc')
        $query = Booking::with([
            'mobil.brand', 'user', 
            'kondisiPengambilan', 'kondisiPengembalian', 
            'ulasanPelanggan', 'ulasanMobil'
        ])->where('status', 'selesai')->orderBy('id', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('nama', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('mobil', function($m) use ($search) {
                      $m->where('plat_nomer', 'like', '%' . $search . '%')
                        ->orWhere('model', 'like', '%' . $search . '%');
                  });
            });
        }

        $riwayat = $query->paginate(10)->appends($request->query());

        return view('admin.kondisiUlasan.index', compact('riwayat'));
    }
}
