<?php

namespace App\Http\Controllers;

use App\Models\VerifikasiAkun;
use App\Services\VerifikasiIdentitasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VerifikasiController extends Controller
{
    public function index()
    {
        $verifikasi = VerifikasiAkun::where('id_user', Auth::id())->first();

        return view('pelanggan.verifikasi.index', compact('verifikasi'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'foto_ktp' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'foto_sim' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'foto_selfie' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $verifikasi = VerifikasiAkun::firstOrNew(['id_user' => Auth::id()]);

        if ($request->hasFile('foto_ktp')) {
            if ($verifikasi->foto_ktp) {
                Storage::disk('public')->delete($verifikasi->foto_ktp);
            }
            $verifikasi->foto_ktp = $request->file('foto_ktp')->store('verifikasi/ktp', 'public');
        }
        if ($request->hasFile('foto_sim')) {
            if ($verifikasi->foto_sim) {
                Storage::disk('public')->delete($verifikasi->foto_sim);
            }
            $verifikasi->foto_sim = $request->file('foto_sim')->store('verifikasi/sim', 'public');
        }
        if ($request->hasFile('foto_selfie')) {
            if ($verifikasi->foto_selfie) {
                Storage::disk('public')->delete($verifikasi->foto_selfie);
            }
            $verifikasi->foto_selfie = $request->file('foto_selfie')->store('verifikasi/selfie', 'public');
        }

        $verifikasi->status = 'pending';
        $verifikasi->save();

        return redirect()->route('pelanggan.verifikasi.index')->with('success', 'Dokumen berhasil diunggah. Menunggu verifikasi admin.');
    }

    public function prosesVerifikasi(Request $request)
    {
        $verifikasi = VerifikasiAkun::where('id_user', Auth::id())->first();
        if (! $verifikasi || ! $verifikasi->foto_ktp || ! $verifikasi->foto_sim || ! $verifikasi->foto_selfie) {
            return back()->with('error', 'Lengkapi semua dokumen terlebih dahulu.');
        }

        $service = new VerifikasiIdentitasService;
        $score = $service->matchFace(
            storage_path('app/public/'.$verifikasi->foto_selfie),
            storage_path('app/public/'.$verifikasi->foto_ktp)
        );

        if ($score >= 0.8) {
            $verifikasi->update(['status' => 'verified', 'verified_at' => now()]);

            return redirect()->route('pelanggan.verifikasi.index')->with('success', 'Verifikasi berhasil! Akun Anda telah terverifikasi.');
        }

        $verifikasi->update(['status' => 'pending']);

        return redirect()->route('pelanggan.verifikasi.index')->with('info', 'Verifikasi otomatis membutuhkan pemeriksaan manual oleh admin.');
    }

    public function adminIndex()
    {
        $verifikasis = VerifikasiAkun::with('user')->where('status', 'pending')->orderBy('created_at', 'desc')->get();

        return view('admin.verifikasi.index', compact('verifikasis'));
    }

    public function approve($id)
    {
        $verifikasi = VerifikasiAkun::findOrFail($id);
        $verifikasi->update(['status' => 'verified', 'verified_at' => now()]);

        return back()->with('success', 'Verifikasi akun disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['catatan_verifikasi' => 'required|string']);
        $verifikasi = VerifikasiAkun::findOrFail($id);
        $verifikasi->update(['status' => 'rejected', 'catatan_verifikasi' => $request->catatan_verifikasi]);

        return back()->with('success', 'Verifikasi akun ditolak.');
    }
}
