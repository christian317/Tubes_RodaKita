<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\PemilikMobil;
use App\Models\PencairanKomisi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index()
    {
        // 1. Hitung total profit bersih perusahaan (30%) dari transaksi yang sudah dibayar/selesai
        $profitPerusahaan = Pembayaran::whereIn('status_pembayaran', ['dibayar', 'lunas', 'selesai'])
            ->selectRaw('SUM(total_pembayaran - komisi_pemilik) as profit')
            ->value('profit') ?? 0;

        // 2. Hitung total saldo yang masih mengendap (belum dicairkan ke mitra)
        $mitras = PemilikMobil::with('user')->get();
        $totalHutangMitra = 0;

        foreach ($mitras as $mitra) {
            // Pendapatan Mitra (70% dari pesanan mobil miliknya)
            $pendapatan = Pembayaran::whereHas('booking.mobil', function ($q) use ($mitra) {
                $q->where('id_pemilik_mobil', $mitra->id_user);
            })->whereIn('status_pembayaran', ['dibayar', 'lunas', 'selesai'])->sum('komisi_pemilik');

            // Total yang sudah ditransfer admin ke mitra ini (hanya yang statusnya disetujui)
            $dicairkan = PencairanKomisi::where('id_pemilik_mobil', $mitra->id_user)->where('status', 'disetujui')->sum('jumlah');

            // Saldo saat ini
            $mitra->saldo_berjalan = $pendapatan - $dicairkan;
            $mitra->total_pendapatan = $pendapatan;

            $totalHutangMitra += $mitra->saldo_berjalan;
        }

        // 3. Ambil riwayat transfer pencairan
        $riwayatPencairan = PencairanKomisi::with('pemilik.user')->orderBy('created_at', 'desc')->get();

        return view('admin.transaksi.index', compact('profitPerusahaan', 'totalHutangMitra', 'mitras', 'riwayatPencairan'));
    }

    public function transferDana(Request $request)
    {
        $request->validate([
            'id_pemilik_mobil' => 'required|exists:pemilik_mobil,id_user',
            'jumlah_transfer' => 'required|numeric|min:10000',
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'catatan' => 'nullable|string',
        ]);

        try {
            $path = $request->file('bukti_transfer')->store('bukti_pencairan', 'public');

            PencairanKomisi::create([
                'id_pemilik_mobil' => $request->id_pemilik_mobil,
                'jumlah' => $request->jumlah_transfer,
                'bukti_transfer' => $path,
                'catatan' => $request->catatan,
            ]);

            return back()->with('success', 'Dana komisi berhasil ditransfer ke mitra dan saldo telah dipotong.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pencairan: '.$e->getMessage());
        }
    }

    public function prosesPencairan(Request $request, $id)
    {
        $request->validate([
            'aksi' => 'required|in:setujui,tolak',
            'bukti_transfer' => 'required_if:aksi,setujui|image|mimes:jpeg,png,jpg|max:2048',
            'catatan_admin' => 'required_if:aksi,tolak|nullable|string',
        ]);

        try {
            $pencairan = PencairanKomisi::findOrFail($id);

            if ($pencairan->status !== 'pending') {
                return back()->with('error', 'Pengajuan pencairan ini sudah diproses sebelumnya.');
            }

            if ($request->aksi === 'setujui') {
                $path = $request->file('bukti_transfer')->store('bukti_pencairan', 'public');
                $pencairan->update([
                    'status' => 'disetujui',
                    'bukti_transfer' => $path,
                    'catatan_admin' => $request->catatan_admin,
                ]);
                return back()->with('success', 'Pengajuan pencairan dana berhasil disetujui dan bukti transfer telah diunggah.');
            } else {
                $pencairan->update([
                    'status' => 'ditolak',
                    'catatan_admin' => $request->catatan_admin,
                ]);
                return back()->with('success', 'Pengajuan pencairan dana telah ditolak dengan catatan.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pencairan: '.$e->getMessage());
        }
    }
}
