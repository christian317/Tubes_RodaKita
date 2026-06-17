<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\KlaimAsuransi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KlaimAsuransiController extends Controller
{
    public function index()
    {
        $klaims = KlaimAsuransi::with('booking.mobil.brand')
            ->where('id_pemilik_mobil', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mitra.klaim.index', compact('klaims'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_booking' => 'required|exists:booking,id',
            'deskripsi_kerusakan' => 'required|string',
            'estimasi_biaya' => 'required|numeric|min:0',
            'foto_bukti.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $booking = Booking::findOrFail($request->id_booking);

        $uploadedPaths = [];
        if ($request->hasFile('foto_bukti')) {
            foreach ($request->file('foto_bukti') as $file) {
                $uploadedPaths[] = $file->store('klaim_asuransi', 'public');
            }
        }

        KlaimAsuransi::create([
            'id_booking' => $request->id_booking,
            'id_pemilik_mobil' => Auth::id(),
            'deskripsi_kerusakan' => $request->deskripsi_kerusakan,
            'estimasi_biaya' => $request->estimasi_biaya,
            'foto_bukti' => $uploadedPaths,
            'status' => 'diajukan',
            'submitted_at' => now(),
        ]);

        return redirect()->route('mitra.klaim.index')->with('success', 'Klaim asuransi berhasil diajukan.');
    }

    public function detail($id)
    {
        $klaim = KlaimAsuransi::with('booking.mobil.brand', 'pemilik')->findOrFail($id);
        if ($klaim->id_pemilik_mobil !== Auth::id()) {
            abort(403);
        }

        return view('mitra.klaim.detail', compact('klaim'));
    }

    public function adminIndex()
    {
        $klaims = KlaimAsuransi::with('booking.mobil.brand', 'pemilik')
            ->orderBy(DB::raw("FIELD(status, 'diajukan', 'ditinjau', 'disetujui', 'ditolak', 'selesai')"))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.klaim.index', compact('klaims'));
    }

    public function proses(Request $request, $id)
    {
        $request->validate([
            'aksi' => 'required|in:setujui,tolak',
            'biaya_disetujui' => 'required_if:aksi,setujui|nullable|numeric|min:0',
            'catatan_klaim' => 'required_if:aksi,tolak|nullable|string',
        ]);

        $klaim = KlaimAsuransi::findOrFail($id);

        if ($request->aksi == 'setujui') {
            $klaim->update([
                'status' => 'disetujui',
                'biaya_disetujui' => $request->biaya_disetujui,
                'catatan_klaim' => $request->catatan_klaim,
                'processed_at' => now(),
            ]);

            return back()->with('success', 'Klaim disetujui.');
        }

        $klaim->update([
            'status' => 'ditolak',
            'catatan_klaim' => $request->catatan_klaim,
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Klaim ditolak.');
    }
}
