<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    /**
     * Display promo list for admin
     */
    public function index()
    {
        $promos = Promo::orderBy('tanggal_kadaluarsa', 'asc')->get();

        return view('admin.promo.index', compact('promos'));
    }

    /**
     * Show form to create promo
     */
    public function create()
    {
        return view('admin.promo.create');
    }

    /**
     * Store new promo code in database
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_promo' => 'required|string|max:50|unique:promo,kode_promo',
            'tipe_potongan' => 'required|in:persen,nominal',
            'nominal_potongan' => 'required|numeric|min:1',
            'minimal_transaksi' => 'required|numeric|min:0',
            'kuota' => 'required|integer|min:1',
            'tanggal_kadaluarsa' => 'required|date|after_or_equal:today',
        ], [
            'kode_promo.required' => 'Kode promo wajib diisi.',
            'kode_promo.unique' => 'Kode promo sudah terdaftar.',
            'kode_promo.max' => 'Kode promo maksimal 50 karakter.',
            'tipe_potongan.required' => 'Tipe potongan wajib dipilih.',
            'tipe_potongan.in' => 'Tipe potongan harus persen atau nominal.',
            'nominal_potongan.required' => 'Nominal potongan wajib diisi.',
            'nominal_potongan.numeric' => 'Nominal potongan harus berupa angka.',
            'nominal_potongan.min' => 'Nominal potongan minimal 1.',
            'minimal_transaksi.required' => 'Minimal transaksi wajib diisi.',
            'minimal_transaksi.numeric' => 'Minimal transaksi harus berupa angka.',
            'minimal_transaksi.min' => 'Minimal transaksi tidak boleh negatif.',
            'kuota.required' => 'Kuota wajib diisi.',
            'kuota.integer' => 'Kuota harus berupa bilangan bulat.',
            'kuota.min' => 'Kuota minimal 1.',
            'tanggal_kadaluarsa.required' => 'Tanggal kadaluarsa wajib diisi.',
            'tanggal_kadaluarsa.date' => 'Format tanggal kadaluarsa tidak valid.',
            'tanggal_kadaluarsa.after_or_equal' => 'Tanggal kadaluarsa harus hari ini atau tanggal setelahnya.',
        ]);

        Promo::create($request->all());

        return redirect()->route('admin.promo.index')->with('success', 'Promo baru berhasil ditambahkan.');
    }

    /**
     * Show edit form for promo
     */
    public function edit($id)
    {
        $promo = Promo::findOrFail($id);

        return view('admin.promo.edit', compact('promo'));
    }

    /**
     * Update existing promo
     */
    public function update(Request $request, $id)
    {
        $promo = Promo::findOrFail($id);

        $request->validate([
            'kode_promo' => 'required|string|max:50|unique:promo,kode_promo,'.$promo->id,
            'tipe_potongan' => 'required|in:persen,nominal',
            'nominal_potongan' => 'required|numeric|min:1',
            'minimal_transaksi' => 'required|numeric|min:0',
            'kuota' => 'required|integer|min:1',
            'tanggal_kadaluarsa' => 'required|date|after_or_equal:today',
        ], [
            'kode_promo.required' => 'Kode promo wajib diisi.',
            'kode_promo.unique' => 'Kode promo sudah terdaftar.',
            'kode_promo.max' => 'Kode promo maksimal 50 karakter.',
            'tipe_potongan.required' => 'Tipe potongan wajib dipilih.',
            'tipe_potongan.in' => 'Tipe potongan harus persen atau nominal.',
            'nominal_potongan.required' => 'Nominal potongan wajib diisi.',
            'nominal_potongan.numeric' => 'Nominal potongan harus berupa angka.',
            'nominal_potongan.min' => 'Nominal potongan minimal 1.',
            'minimal_transaksi.required' => 'Minimal transaksi wajib diisi.',
            'minimal_transaksi.numeric' => 'Minimal transaksi harus berupa angka.',
            'minimal_transaksi.min' => 'Minimal transaksi tidak boleh negatif.',
            'kuota.required' => 'Kuota wajib diisi.',
            'kuota.integer' => 'Kuota harus berupa bilangan bulat.',
            'kuota.min' => 'Kuota minimal 1.',
            'tanggal_kadaluarsa.required' => 'Tanggal kadaluarsa wajib diisi.',
            'tanggal_kadaluarsa.date' => 'Format tanggal kadaluarsa tidak valid.',
            'tanggal_kadaluarsa.after_or_equal' => 'Tanggal kadaluarsa harus hari ini atau tanggal setelahnya.',
        ]);

        $promo->update($request->all());

        return redirect()->route('admin.promo.index')->with('success', 'Promo berhasil diperbarui.');
    }

    /**
     * Delete promo code
     */
    public function destroy($id)
    {
        $promo = Promo::findOrFail($id);
        $promo->delete();

        return redirect()->back()->with('success', 'Promo berhasil dihapus.');
    }

    /**
     * AJAX action to validate applied promo code
     */
    public function checkPromo(Request $request)
    {
        $request->validate([
            'kode_promo' => 'required|string',
            'total_bayar' => 'required|numeric',
        ]);

        $promo = Promo::where('kode_promo', $request->kode_promo)->first();

        if (! $promo) {
            return response()->json([
                'success' => false,
                'message' => 'Kode promo tidak ditemukan.',
            ]);
        }

        // Check expiration
        if (Carbon::parse($promo->tanggal_kadaluarsa)->isPast() && ! Carbon::parse($promo->tanggal_kadaluarsa)->isToday()) {
            return response()->json([
                'success' => false,
                'message' => 'Kode promo sudah kadaluarsa.',
            ]);
        }

        // Check quota
        if ($promo->kuota <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Kuota pemakaian promo sudah habis.',
            ]);
        }

        // Check minimum transaction
        if ($request->total_bayar < $promo->minimal_transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Total sewa belum memenuhi minimal transaksi Rp '.number_format($promo->minimal_transaksi, 0, ',', '.').'.',
            ]);
        }

        // Calculate discount
        $potongan = 0;
        if ($promo->tipe_potongan === 'persen') {
            $potongan = ($promo->nominal_potongan / 100) * $request->total_bayar;
        } else {
            $potongan = $promo->nominal_potongan;
        }

        // Ensure discount is not greater than the total amount
        if ($potongan > $request->total_bayar) {
            $potongan = $request->total_bayar;
        }

        return response()->json([
            'success' => true,
            'id_promo' => $promo->id,
            'potongan' => $potongan,
            'message' => 'Kode promo berhasil digunakan!',
        ]);
    }
}
