<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;
use App\Models\Mobil;
use App\Models\Booking;
use App\Models\JadwalLiburan;

class ComprehensiveAppTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $mitra;
    protected $pelanggan;
    protected $brandId;
    protected $kategoriId;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed basic roles
        DB::table('role')->insert([
            ['id' => 1, 'nama_role' => 'admin'],
            ['id' => 2, 'nama_role' => 'pelanggan'],
            ['id' => 3, 'nama_role' => 'pemilik'],
        ]);

        // Create Admin
        $adminId = DB::table('user')->insertGetId([
            'id_role' => 1,
            'nama' => 'Admin Utama',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'alamat' => 'Kantor Pusat',
            'no_telepon' => '081234567890',
        ]);
        $this->admin = User::find($adminId);

        // Create Mitra/Pemilik
        $mitraId = DB::table('user')->insertGetId([
            'id_role' => 3,
            'nama' => 'Hendra Wijaya',
            'email' => 'mitra@gmail.com',
            'password' => Hash::make('password123'),
            'alamat' => 'Jl. Mitra No. 1',
            'no_telepon' => '083334567891',
        ]);
        $this->mitra = User::find($mitraId);

        DB::table('pemilik_mobil')->insert([
            'id_user' => $mitraId,
            'nama_bank' => 'BCA',
            'nomor_rekening' => '1234567890',
            'nomor_ktp' => '3171012345670001',
        ]);

        // Create Pelanggan
        $pelangganId = DB::table('user')->insertGetId([
            'id_role' => 2,
            'nama' => 'Budi Santoso',
            'email' => 'pelanggan@gmail.com',
            'password' => Hash::make('password123'),
            'alamat' => 'Jl. Mawar No. 12',
            'no_telepon' => '082234567891',
        ]);
        $this->pelanggan = User::find($pelangganId);

        // Seed Brand & Category
        $this->brandId = DB::table('brand')->insertGetId(['nama_brand' => 'Toyota']);
        $this->kategoriId = DB::table('kategori')->insertGetId(['nama_kategori' => 'SUV']);
    }

    /**
     * Test Admin User Management
     */
    public function test_admin_can_manage_users(): void
    {
        $this->actingAs($this->admin);

        // 1. Create a User (store)
        $response = $this->post(route('admin.user.store'), [
            'id_role' => 2,
            'nama' => 'Test Customer',
            'email' => 'test_cust@example.com',
            'password' => 'password123',
            'alamat' => 'Jl. Test Address',
            'no_telepon' => '0899999999',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('user', [
            'email' => 'test_cust@example.com',
            'nama' => 'Test Customer',
        ]);

        $createdUser = User::where('email', 'test_cust@example.com')->first();

        // 2. Edit/Update User
        $updateResponse = $this->put(route('admin.user.update', ['id' => $createdUser->id]), [
            'nama' => 'Updated Customer Name',
            'email' => 'test_cust@example.com',
            'alamat' => 'Jl. Updated Address',
            'no_telepon' => '0899999999',
        ]);

        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('user', [
            'id' => $createdUser->id,
            'nama' => 'Updated Customer Name',
        ]);

        // 3. Delete User
        $deleteResponse = $this->delete(route('admin.user.destroy', ['id' => $createdUser->id]));
        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('user', [
            'id' => $createdUser->id,
        ]);
    }

    /**
     * Test Admin Car Management
     */
    public function test_admin_can_manage_cars(): void
    {
        $this->actingAs($this->admin);

        // 1. Create a Car
        $response = $this->post(route('admin.mobil.store'), [
            'id_brand' => $this->brandId,
            'id_kategori' => $this->kategoriId,
            'id_pemilik_mobil' => $this->mitra->id,
            'model' => 'Fortuner Sporty',
            'plat_nomer' => 'B 7777 ABC',
            'harga_sewa' => 500000.00,
            'transmisi' => 'Automatic',
            'kapasitas_penumpang' => 7,
            'tahun' => 2023,
            'gambar' => \Illuminate\Http\UploadedFile::fake()->image('fortuner.jpg'),
            'status_mobil' => 'tersedia',
            'deskripsi' => 'Fortuner gagah.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('mobil', [
            'model' => 'Fortuner Sporty',
            'plat_nomer' => 'B 7777 ABC',
        ]);

        $createdMobil = Mobil::where('plat_nomer', 'B 7777 ABC')->first();

        // 2. Update Car
        $updateResponse = $this->put(route('admin.mobil.update', ['id' => $createdMobil->id]), [
            'id_brand' => $this->brandId,
            'model' => 'Fortuner Updated',
            'id_kategori' => $this->kategoriId,
            'transmisi' => 'Automatic',
            'kapasitas_penumpang' => 7,
            'tahun' => 2023,
            'plat_nomer' => 'B 7777 ABC',
            'id_pemilik_mobil' => $this->mitra->id,
            'harga_sewa' => 550000.00,
            'status_mobil' => 'tersedia',
            'deskripsi' => 'Fortuner gagah updated.',
        ]);

        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('mobil', [
            'id' => $createdMobil->id,
            'model' => 'Fortuner Updated',
        ]);

        // 3. Delete Car
        $deleteResponse = $this->delete(route('admin.mobil.destroy', ['id' => $createdMobil->id]));
        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('mobil', [
            'id' => $createdMobil->id,
        ]);
    }

    /**
     * Test Mitra views Monitoring & Commissions page
     */
    public function test_mitra_can_monitor_cars_and_commissions(): void
    {
        $this->actingAs($this->mitra);

        // Check dashboard status
        $response1 = $this->get(route('mitra.monitoringMobil.index'));
        $response1->assertStatus(200);

        $response2 = $this->get(route('mitra.komisi.index'));
        $response2->assertStatus(200);
    }

    /**
     * Test Pelanggan submits reviews after finished rental
     */
    public function test_pelanggan_can_submit_reviews(): void
    {
        // Create a car owned by Mitra
        $mobilId = DB::table('mobil')->insertGetId([
            'id_brand' => $this->brandId,
            'id_kategori' => $this->kategoriId,
            'id_pemilik_mobil' => $this->mitra->id,
            'model' => 'Avanza',
            'plat_nomer' => 'B 1111 AAA',
            'harga_sewa' => 300000.00,
            'transmisi' => 'Manual',
            'kapasitas_penumpang' => 7,
            'tahun' => 2021,
            'status_katalog' => 1,
            'status_mobil' => 'tersedia',
            'gambar' => 'avanza.jpg',
        ]);

        // Create completed booking (selesai)
        $bookingId = DB::table('booking')->insertGetId([
            'id_user' => $this->pelanggan->id,
            'id_mobil' => $mobilId,
            'tanggal_mulai' => now()->subDays(3)->format('Y-m-d H:i:s'),
            'tanggal_selesai' => now()->subDays(1)->format('Y-m-d H:i:s'),
            'waktu_mulai' => '09:00',
            'waktu_selesai' => '09:00:00',
            'status' => 'selesai',
            'tipe_layanan' => 'lepas_kunci',
        ]);

        $this->actingAs($this->pelanggan);

        // Post review
        $response = $this->post(route('pelanggan.ulasan.store', ['id' => $bookingId]), [
            'rating' => 5,
            'catatan' => 'Sangat memuaskan, mobil bersih!',
            'tipe' => 'mobil',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ulasan', [
            'id_booking' => $bookingId,
            'rating' => 5,
            'catatan' => 'Sangat memuaskan, mobil bersih!',
            'tipe' => 'mobil',
        ]);
    }

    /**
     * Test Pelanggan manages travel schedule (Jadwal Liburan) during active booking
     */
    public function test_pelanggan_can_manage_travel_schedule(): void
    {
        $mobilId = DB::table('mobil')->insertGetId([
            'id_brand' => $this->brandId,
            'id_kategori' => $this->kategoriId,
            'id_pemilik_mobil' => $this->mitra->id,
            'model' => 'Creta SUV',
            'plat_nomer' => 'B 2222 BBB',
            'harga_sewa' => 450000.00,
            'transmisi' => 'Automatic',
            'kapasitas_penumpang' => 5,
            'tahun' => 2022,
            'status_katalog' => 1,
            'status_mobil' => 'tersedia',
            'gambar' => 'creta.jpg',
        ]);

        // Create active booking (dibayar)
        $bookingId = DB::table('booking')->insertGetId([
            'id_user' => $this->pelanggan->id,
            'id_mobil' => $mobilId,
            'tanggal_mulai' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'tanggal_selesai' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'waktu_mulai' => '09:00',
            'waktu_selesai' => '09:00:00',
            'status' => 'dibayar',
            'tipe_layanan' => 'lepas_kunci',
        ]);

        $this->actingAs($this->pelanggan);

        // 1. Add Travel Schedule
        $response = $this->post(route('pelanggan.jadwal.store', ['id_booking' => $bookingId]), [
            'tanggal' => now()->addDays(3)->format('Y-m-d'),
            'jam_mulai' => '10:00',
            'jam_selesai' => '15:00',
            'kegiatan' => 'Jalan-jalan ke Pantai Pangandaran',
            'lokasi' => 'Pangandaran',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('jadwal_liburans', [
            'id_booking' => $bookingId,
            'kegiatan' => 'Jalan-jalan ke Pantai Pangandaran',
        ]);

        $schedule = JadwalLiburan::where('id_booking', $bookingId)->first();

        // 2. Delete Travel Schedule
        $deleteResponse = $this->delete(route('pelanggan.jadwal.destroy', ['id' => $schedule->id]));
        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('jadwal_liburans', [
            'id' => $schedule->id,
        ]);
    }
}
