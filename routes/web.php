<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminJadwalBookingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JadwalLiburanController;
use App\Http\Controllers\KlaimAsuransiController;
use App\Http\Controllers\KondisiUlasanController;
use App\Http\Controllers\MitraMobilController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\MonitoringMobilController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PendapatanKomisiController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\RiwayatBookingController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UlasanPelangganController;
use App\Http\Controllers\VerifikasiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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
    // kategori
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
    // jadwal booking
    Route::get('/admin/booking', [AdminJadwalBookingController::class, 'index'])->name('admin.booking.index');
    Route::post('/admin/booking/{id}/approve', [AdminJadwalBookingController::class, 'approve'])->name('admin.booking.approve');
    Route::post('/admin/booking/{id}/reject', [AdminJadwalBookingController::class, 'reject'])->name('admin.booking.reject');
    Route::post('/admin/booking/{id}/serahkan', [AdminJadwalBookingController::class, 'serahkanMobil'])->name('admin.booking.serahkan');
    Route::post('/admin/booking/{id}/terima', [AdminJadwalBookingController::class, 'terimaMobil'])->name('admin.booking.terima');
    // riwayat kondisi & ulasan
    Route::get('/admin/kondisi-ulasan', [KondisiUlasanController::class, 'index'])->name('admin.kondisiUlasan.index');
    // transaksi
    Route::get('/admin/keuangan', [TransaksiController::class, 'index'])->name('admin.transaksi.index');
    Route::post('/admin/keuangan/transfer', [TransaksiController::class, 'transferDana'])->name('admin.transaksi.transfer');

    // manajemen promo
    Route::get('/admin/promo', [PromoController::class, 'index'])->name('admin.promo.index');
    Route::get('/admin/promo/create', [PromoController::class, 'create'])->name('admin.promo.create');
    Route::post('/admin/promo/store', [PromoController::class, 'store'])->name('admin.promo.store');
    Route::get('/admin/promo/{id}/edit', [PromoController::class, 'edit'])->name('admin.promo.edit');
    Route::put('/admin/promo/{id}', [PromoController::class, 'update'])->name('admin.promo.update');
    Route::delete('/admin/promo/{id}', [PromoController::class, 'destroy'])->name('admin.promo.destroy');
});

// user
Route::middleware(['auth', 'role:2'])->group(function () {
    Route::get('pelanggan/dashboard', [PelangganController::class, 'dashboard'])->name('pelanggan.dashboard');
    Route::get('/pelanggan/mobil/{id}', [PelangganController::class, 'detail_mobil'])->name('pelanggan.mobil.detail_mobil');
    // Pastikan berada di dalam middleware auth (pelanggan harus login)
    Route::get('/pelanggan/checkout/{id_mobil}', [PelangganController::class, 'checkout'])->name('pelanggan.order.checkout');
    Route::post('/pelanggan/checkout/proses', [PelangganController::class, 'prosesCheckout'])->name('pelanggan.order.checkout.proses');
    // Rute Daftar Pesanan Pelanggan
    Route::get('/pelanggan/riwayat-booking', [RiwayatBookingController::class, 'index'])->name('pelanggan.riwayatBooking.index');
    Route::post('/pelanggan/pesanan/{id}/ulasan', [RiwayatBookingController::class, 'simpanUlasanMobil'])->name('pelanggan.ulasan.store');
    // Route Jadwal Liburan
    Route::get('/pelanggan/jadwal-liburan', [JadwalLiburanController::class, 'index'])->name('pelanggan.jadwal.index');
    Route::get('/pelanggan/jadwal-liburan/{id_booking}', [JadwalLiburanController::class, 'detail'])->name('pelanggan.jadwal.detail');
    Route::post('/pelanggan/jadwal-liburan/{id_booking}', [JadwalLiburanController::class, 'create'])->name('pelanggan.jadwal.store');
    Route::delete('/pelanggan/jadwal-liburan/hapus/{id}', [JadwalLiburanController::class, 'destroy'])->name('pelanggan.jadwal.destroy');

    // check promo
    Route::post('/pelanggan/promo/check', [PromoController::class, 'checkPromo'])->name('pelanggan.promo.check');
});

// mitra penyewa mobil
Route::middleware(['auth', 'role:3'])->group(function () {
    Route::get('mitra/dashboard', [MitraMobilController::class, 'dashboard'])->name('mitra.dashboard');
    Route::get('/mitra/monitoring-mobil', [MonitoringMobilController::class, 'index'])->name('mitra.monitoringMobil.index');
    Route::get('/mitra/komisi', [PendapatanKomisiController::class, 'index'])->name('mitra.komisi.index');
    Route::get('/mitra/komisi/mobil/{id}', [PendapatanKomisiController::class, 'detail'])->name('mitra.komisi.detail');
    Route::get('/mitra/ulasanPelanggan', [UlasanPelangganController::class, 'index'])->name('mitra.ulasanPelanggan.index');
    Route::get('/mitra/ulasanPelanggan/mobil/{id}', [UlasanPelangganController::class, 'detail'])->name('mitra.ulasanPelanggan.detail');
});

// Rute publik khusus menerima respon notifikasi pembayaran Midtrans Snap

// Verifikasi Akun Pelanggan
Route::middleware(['auth', 'role:2'])->group(function () {
    Route::get('/pelanggan/verifikasi', [VerifikasiController::class, 'index'])->name('pelanggan.verifikasi.index');
    Route::post('/pelanggan/verifikasi/upload', [VerifikasiController::class, 'upload'])->name('pelanggan.verifikasi.upload');
    Route::post('/pelanggan/verifikasi/proses', [VerifikasiController::class, 'prosesVerifikasi'])->name('pelanggan.verifikasi.proses');
});

// Admin Verifikasi
Route::middleware(['auth', 'role:1'])->group(function () {
    Route::get('/admin/verifikasi', [VerifikasiController::class, 'adminIndex'])->name('admin.verifikasi.index');
    Route::post('/admin/verifikasi/{id}/approve', [VerifikasiController::class, 'approve'])->name('admin.verifikasi.approve');
    Route::post('/admin/verifikasi/{id}/reject', [VerifikasiController::class, 'reject'])->name('admin.verifikasi.reject');
});

// Mitra Klaim Asuransi
Route::middleware(['auth', 'role:3'])->group(function () {
    Route::get('/mitra/klaim', [KlaimAsuransiController::class, 'index'])->name('mitra.klaim.index');
    Route::post('/mitra/klaim/store', [KlaimAsuransiController::class, 'store'])->name('mitra.klaim.store');
    Route::get('/mitra/klaim/{id}', [KlaimAsuransiController::class, 'detail'])->name('mitra.klaim.detail');
});

// Admin Klaim Asuransi
Route::middleware(['auth', 'role:1'])->group(function () {
    Route::get('/admin/klaim', [KlaimAsuransiController::class, 'adminIndex'])->name('admin.klaim.index');
    Route::post('/admin/klaim/{id}/proses', [KlaimAsuransiController::class, 'proses'])->name('admin.klaim.proses');
});

Route::post('/midtrans/callback', [PelangganController::class, 'handleNotification']);
