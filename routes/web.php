<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\MitraMobilController;
use App\Http\Controllers\MobilController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost'])->name('login.post');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerPost'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// admin
Route::middleware(['auth', 'role:1'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    // manajemen mobil
    Route::get('/admin/mobil/index', [MobilController::class, 'index_mobil'])->name('admin.mobil.index');
    Route::get('/admin/mobil/create', [MobilController::class, 'createMobil'])->name('admin.mobil.create');
    Route::post('/admin/mobil/store', [MobilController::class, 'store_mobil'])->name('admin.mobil.store');
    Route::get('/admin/mobil/{id}/edit', [MobilController::class, 'edit_mobil'])->name('admin.mobil.edit');
    Route::put('/admin/mobil/update/{id}', [MobilController::class, 'update_mobil'])->name('admin.mobil.update');
    Route::delete('/admin/mobil/destroy/{id}', [MobilController::class, 'destroy_mobil'])->name('admin.mobil.destroy');
    // brand 
    Route::get('/admin/mobil/brand/create', [MobilController::class, 'createBrand'])->name('admin.mobil.brand.create');
    Route::post('/admin/mobil/brand', [MobilController::class, 'storeBrand'])->name('admin.mobil.brand.store');
    Route::get('/admin/mobil/brand/{id}/edit', [MobilController::class, 'editBrand'])->name('admin.mobil.brand.edit');
    Route::put('/admin/mobil/brand/{id}', [MobilController::class, 'updateBrand'])->name('admin.mobil.brand.update');
    Route::delete('/admin/mobil/brand/{id}', [MobilController::class, 'destroyBrand'])->name('admin.mobil.brand.destroy');
    //kategori
    Route::get('/admin/mobil/kategori/create', [MobilController::class, 'createKategori'])->name('admin.mobil.kategori.create');
    Route::post('/admin/mobil/kategori', [MobilController::class, 'storeKategori'])->name('admin.mobil.kategori.store');
    Route::get('/admin/mobil/kategori/{id}/edit', [MobilController::class, 'editKategori'])->name('admin.mobil.kategori.edit');
    Route::put('/admin/mobil/kategori/{id}', [MobilController::class, 'updateKategori'])->name('admin.mobil.kategori.update');
    Route::delete('/admin/mobil/kategori/{id}', [MobilController::class, 'destroyKategori'])->name('admin.mobil.kategori.destroy');
    // Manajemen Pengguna (Mitra & Pelanggan)
    Route::get('/admin/user', [AdminController::class, 'index_user'])->name('admin.user.index');
    Route::get('/admin/user/create', [AdminController::class, 'create_user'])->name('admin.user.create');
    Route::post('/admin/user', [AdminController::class, 'store_user'])->name('admin.user.store');
    Route::get('/admin/user/{id}/edit', [AdminController::class, 'edit_user'])->name('admin.user.edit');
    Route::put('/admin/user/{id}', [AdminController::class, 'update_user'])->name('admin.user.update');
    Route::delete('/admin/user/{id}', [AdminController::class, 'destroy_user'])->name('admin.user.destroy');
});



// user
Route::middleware(['auth', 'role:2'])->group(function () {
    Route::get('pelanggan/dashboard', [PelangganController::class, 'dashboard'])->name('pelanggan.dashboard');
    Route::get('/pelanggan/mobil/{id}', [PelangganController::class, 'detail_mobil'])->name('pelanggan.mobil.detail_mobil');
    // Pastikan berada di dalam middleware auth (pelanggan harus login)
    Route::get('/pelanggan/checkout/{id_mobil}', [PelangganController::class, 'checkout'])->name('pelanggan.order.checkout');
    Route::post('/pelanggan/checkout/proses', [PelangganController::class, 'prosesCheckout'])->name('pelanggan.order.checkout.proses');
});


// mitra penyewa mobil
Route::middleware(['auth', 'role:3'])->group(function () {
    Route::get('mitra/dashboard', [MitraMobilController::class, 'dashboard'])->name('mitra.dashboard');
});