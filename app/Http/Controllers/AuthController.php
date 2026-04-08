<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function loginPost(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $role = Auth::user()->id_role;
            
            if ($role == 1) {
                return redirect()->intended('/admin/dashboard');
            } elseif ($role == 2) {
                return redirect()->intended('/pelanggan/dashboard');
            } elseif ($role == 3) {
                return redirect()->intended('/mitra/dashboard');
            }

            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function registerPost(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:user',
            'password' => 'required|string|min:6',
            'alamat' => 'required|string|max:500',
            'no_telepon' => 'required|string|max:15',
            'role' => 'required|in:pelanggan,pemilik'
        ]);

        // Cari ID Role berdasarkan input (Default ke pelanggan)
        // Pastikan nama_role di tabel role Anda sesuai ('pelanggan' atau 'pemilik')
        $role = DB::table('role')->where('nama_role', $request->role)->first();

        // Jika tidak ketemu, fallback (misal ID 2 adalah default Pelanggan)
        $id_role = $role ? $role->id : 2;

        User::create([
            'id_role' => $id_role,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon,
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan masuk.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
