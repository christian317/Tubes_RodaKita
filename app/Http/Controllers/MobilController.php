<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mobil;
use App\Models\Brand;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class MobilController extends Controller
{
    public function createBrand()
    {
        $brands = Brand::all();
        return view('admin.mobil.brand.create', compact('brands'));
    }


    public function storeBrand(Request $request)
    {
        $request->validate([
            'nama_brand' => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        Brand::create([
            'nama_brand' => $request->nama_brand,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->back()->with('success', 'Brand/Merk baru berhasil ditambahkan.');
    }

    public function editBrand($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.mobil.brand.edit', compact('brand'));
    }

    public function updateBrand(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);
        $brands = Brand::all();
        $request->validate([
            'nama_brand' => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        $brand->update([
            'nama_brand' => $request->nama_brand,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.mobil.brand.create')->with('success', 'Brand berhasil diperbarui.');
    }

    public function destroyBrand($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }


    public function createKategori()
    {
        $kategoris = Kategori::all();
        return view('admin.mobil.kategori.create', compact('kategoris'));
    }


    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:50',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->back()->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    public function editKategori($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('admin.mobil.kategori.edit', compact('kategori'))->with('success', 'Kategori berhasil diperbarui.');
    }

    public function updateKategori(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategoris = Kategori::all();

        $request->validate([
            'nama_kategori' => 'required|string|max:50',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.mobil.kategori.create')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroyKategori($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }


    public function index_mobil()
    {
        // Ambil data beserta relasinya
        $mobils = Mobil::with(['brand', 'kategori', 'pemilik'])->get();

        // Ambil data untuk dropdown di modal
        $brands = Brand::all();
        $kategoris = Kategori::all();
        // Ambil user yang rolenya pemilik (id_role = 3)
        $pemiliks = User::where('id_role', 3)->get();

        return view('admin.mobil.index', compact('mobils', 'brands', 'kategoris', 'pemiliks'));
    }


    public function createMobil()
    {
        $brands = Brand::all();
        $kategoris = Kategori::all();
        $pemiliks = User::where('id_role', 3)->get();

        return view('admin.mobil.create', compact('brands', 'kategoris', 'pemiliks'));
    }


    public function store_mobil(Request $request)
    {
        $request->validate([
            'id_brand' => 'required|integer',
            'id_kategori' => 'required|integer',
            'id_pemilik_mobil' => 'required|integer',
            'model' => 'required|string|max:100',
            'plat_nomer' => 'required|string|max:20',
            'harga_sewa' => 'required|numeric',
            'transmisi' => 'required|string|max:50',
            'kapasitas_penumpang' => 'required|integer',
            'tahun' => 'required|integer',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'status_mobil' => 'required|string|max:20',
            'deskripsi' => 'nullable|string',
        ]);

        // Upload Gambar ke folder storage/app/public/mobil
        $gambarPath = $request->file('gambar')->store('mobil', 'public');

        Mobil::create([
            'id_brand' => $request->id_brand,
            'id_kategori' => $request->id_kategori,
            'id_pemilik_mobil' => $request->id_pemilik_mobil,
            'model' => $request->model,
            'plat_nomer' => $request->plat_nomer,
            'harga_sewa' => $request->harga_sewa,
            'transmisi' => $request->transmisi,
            'kapasitas_penumpang' => $request->kapasitas_penumpang,
            'tahun' => $request->tahun,
            'status_katalog' => $request->has('status_katalog') ? 1 : 0,
            'status_mobil' => 'tersedia',
            'gambar' => $gambarPath,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.mobil.index')->with('success', 'Mobil baru berhasil ditambahkan.');
    }

    public function edit_mobil($id)
    {
        $mobil = Mobil::findOrFail($id);
        $brands = Brand::all();
        $kategoris = Kategori::all();
        $pemiliks = User::where('id_role', 3)->get();

        return view('admin.mobil.edit', compact('mobil', 'brands', 'kategoris', 'pemiliks'));
    }

    public function update_mobil(Request $request, $id)
    {
        $mobil = Mobil::findOrFail($id);

        $request->validate([
            'id_brand' => 'required|integer',
            'model' => 'required|string|max:100',
            'id_kategori' => 'required|integer',
            'transmisi' => 'required|string|max:50',
            'kapasitas_penumpang' => 'required|integer',
            'tahun' => 'required|integer',
            'plat_nomer' => 'required|string|max:20',
            'id_pemilik_mobil' => 'required|integer',
            'harga_sewa' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status_mobil' => 'required|string|max:20',
        ]);

        $dataUpdate = [
            'id_brand' => $request->id_brand,
            'model' => $request->model,
            'id_kategori' => $request->id_kategori,
            'transmisi' => $request->transmisi,
            'kapasitas_penumpang' => $request->kapasitas_penumpang,
            'tahun' => $request->tahun,
            'plat_nomer' => $request->plat_nomer,
            'id_pemilik_mobil' => $request->id_pemilik_mobil,
            'harga_sewa' => $request->harga_sewa,
            'status_katalog' => $request->has('status_katalog') ? 1 : 0,
            'status_mobil' => $request->status_mobil,
            'deskripsi' => $request->deskripsi,
        ];

        if ($request->hasFile('gambar')) {
            if ($mobil->gambar) {
                Storage::disk('public')->delete($mobil->gambar);
            }
            $dataUpdate['gambar'] = $request->file('gambar')->store('mobil', 'public');
        }

        $mobil->update($dataUpdate);

        return redirect()->route('admin.mobil.index')->with('success', 'Data mobil berhasil diperbarui.');
    }


    public function destroy_mobil($id)
    {
        $mobil = Mobil::findOrFail($id);
        if ($mobil->gambar) {
            Storage::disk('public')->delete($mobil->gambar);
        }
        $mobil->delete();

        return redirect()->back()->with('success', 'Mobil berhasil dihapus.');
    }
}
