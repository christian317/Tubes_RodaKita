<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PemilikMobil;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function index_user()
    {
        // Pisahkan query untuk dikirim ke masing-masing Tab di view
        $mitras = User::with('pemilikMobil')->where('id_role', 3)->get();
        $pelanggans = User::where('id_role', 2)->get();

        return view('admin.user.index', compact('mitras', 'pelanggans'));
    }

    public function create_user()
    {
        return view('admin.user.create');
    }

    public function store_user(Request $request)
    {
        $rules = [
            'id_role' => 'required|in:2,3',
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:user,email',
            'password' => 'required|string|min:6',
            'alamat' => 'required|string|max:500',
            'no_telepon' => 'required|string|max:15',
        ];

        if ($request->id_role == 3) {
            $rules['nama_bank'] = 'required|string|max:50';
            $rules['nomor_rekening'] = 'required|string|max:50';
            $rules['nomor_ktp'] = 'required|string|max:50|unique:pemilik_mobil,nomor_ktp';
        }

        $request->validate($rules);

        DB::beginTransaction();
        try {
            $user = User::create([
                'id_role' => $request->id_role,
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
            ]);

            if ($request->id_role == 3) {
                PemilikMobil::create([
                    'id_user' => $user->id,
                    'nama_bank' => $request->nama_bank,
                    'nomor_rekening' => $request->nomor_rekening,
                    'nomor_ktp' => $request->nomor_ktp,
                ]);
            }

            DB::commit();
            $tipe = $request->id_role == 3 ? 'Mitra' : 'Pelanggan';
            return redirect()->route('admin.user.index')->with('success', "Akun $tipe baru berhasil ditambahkan.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit_user($id)
    {
        $user = User::with('pemilikMobil')->findOrFail($id);
        return view('admin.user.edit', compact('user'));
    }


    public function update_user(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:user,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'alamat' => 'required|string|max:500',
            'no_telepon' => 'required|string|max:15',
        ];

        if ($user->id_role == 3) {
            $rules['nama_bank'] = 'required|string|max:50';
            $rules['nomor_rekening'] = 'required|string|max:50';
            $rules['nomor_ktp'] = 'required|string|max:50|unique:pemilik_mobil,nomor_ktp,' . $user->id . ',id_user';
        }

        $request->validate($rules);
        DB::beginTransaction();
        try {
            $dataUser = [
                'nama' => $request->nama,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
            ];

            if ($request->filled('password')) {
                $dataUser['password'] = Hash::make($request->password);
            }

            $user->update($dataUser);
            if ($user->id_role == 3) {
                PemilikMobil::updateOrCreate(
                    ['id_user' => $user->id],
                    [
                        'nama_bank' => $request->nama_bank,
                        'nomor_rekening' => $request->nomor_rekening,
                        'nomor_ktp' => $request->nomor_ktp,
                    ]
                );
            }

            DB::commit();
            $tipe = $user->id_role == 3 ? 'Mitra' : 'Pelanggan';
            return redirect()->route('admin.user.index')->with('success', "Data $tipe berhasil diperbarui.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal memperbarui data: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy_user($id)
    {
        $user = User::findOrFail($id);

        DB::beginTransaction();
        try {
            // Jika dia mitra, hapus data relasinya dulu
            if ($user->id_role == 3) {
                PemilikMobil::where('id_user', $user->id)->delete();
            }
            // Hapus akun utama
            $user->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Pengguna berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal menghapus: ' . $e->getMessage()]);
        }
    }
}
