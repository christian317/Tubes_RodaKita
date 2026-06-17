<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;
use App\Models\VerifikasiAkun;

class SafetyAndBookingTest extends TestCase
{
    use RefreshDatabase;

    protected $adminId;
    protected $mitraId;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed basic roles and admin user
        DB::table('role')->insert([
            ['id' => 1, 'nama_role' => 'admin'],
            ['id' => 2, 'nama_role' => 'pelanggan'],
            ['id' => 3, 'nama_role' => 'pemilik'],
        ]);

        // Create Admin
        $this->adminId = DB::table('user')->insertGetId([
            'id_role' => 1,
            'nama' => 'Admin Utama',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'alamat' => 'Kantor Pusat',
            'no_telepon' => '081234567890',
        ]);

        // Create Mitra/Pemilik
        $this->mitraId = DB::table('user')->insertGetId([
            'id_role' => 3,
            'nama' => 'Hendra Wijaya',
            'email' => 'mitra@gmail.com',
            'password' => Hash::make('password123'),
            'alamat' => 'Jl. Mitra No. 1',
            'no_telepon' => '083334567891',
        ]);

        DB::table('pemilik_mobil')->insert([
            'id_user' => $this->mitraId,
            'nama_bank' => 'BCA',
            'nomor_rekening' => '1234567890',
            'nomor_ktp' => '3171012345670001',
        ]);

        // Mock Midtrans Snap class static method
        $mock = \Mockery::mock('alias:Midtrans\Snap');
        $mock->shouldReceive('getSnapToken')
             ->zeroOrMoreTimes()
             ->andReturn('fake-snap-token');
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }

    /**
     * Helper to create a car
     */
    protected function createCar($modelName = 'Avanza', $plat = 'B 1234 ABC'): int
    {
        $brandId = DB::table('brand')->insertGetId(['nama_brand' => 'Toyota']);
        $kategoriId = DB::table('kategori')->insertGetId(['nama_kategori' => 'SUV']);

        return DB::table('mobil')->insertGetId([
            'id_brand' => $brandId,
            'id_kategori' => $kategoriId,
            'id_pemilik_mobil' => $this->mitraId,
            'model' => $modelName,
            'plat_nomer' => $plat,
            'harga_sewa' => 300000.00,
            'transmisi' => 'Automatic',
            'kapasitas_penumpang' => 7,
            'tahun' => 2022,
            'status_katalog' => 1,
            'status_mobil' => 'tersedia',
            'gambar' => 'avanza.jpg',
            'deskripsi' => 'Mobil nyaman.',
        ]);
    }

    /**
     * TC-1: No overlap booking succeeds
     */
    public function test_booking_no_overlap_succeeds(): void
    {
        // Setup user (pelanggan)
        $pelangganId = DB::table('user')->insertGetId([
            'id_role' => 2,
            'nama' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'alamat' => 'Jl. Jalan',
            'no_telepon' => '081234567891',
        ]);

        $pelanggan = User::find($pelangganId);

        // Mark user verified
        DB::table('verifikasi_akun')->insert([
            'id_user' => $pelangganId,
            'status' => 'verified',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $mobilId = $this->createCar('Avanza', 'B 1234 ABC');

        $this->actingAs($pelanggan);
        
        // Book dates 1-3 (relative to today)
        $response1 = $this->post(route('pelanggan.order.checkout.proses'), [
            'id_mobil' => $mobilId,
            'waktu_mulai' => '09:00',
            'waktu_selesai' => '18:00',
            'tanggal_mulai' => now()->addDays(1)->format('Y-m-d'),
            'tanggal_selesai' => now()->addDays(3)->format('Y-m-d'),
            'tipe_layanan' => 'lepas_kunci',
            'foto_ktp' => \Illuminate\Http\UploadedFile::fake()->image('ktp.jpg'),
        ]);
        
        $response1->assertStatus(200); // Renders checkout view with Midtrans token
        $this->assertDatabaseHas('booking', [
            'id_mobil' => $mobilId,
            'id_user' => $pelangganId,
        ]);

        // Book dates 5-7 (no overlap)
        $response2 = $this->post(route('pelanggan.order.checkout.proses'), [
            'id_mobil' => $mobilId,
            'waktu_mulai' => '09:00',
            'waktu_selesai' => '18:00',
            'tanggal_mulai' => now()->addDays(5)->format('Y-m-d'),
            'tanggal_selesai' => now()->addDays(7)->format('Y-m-d'),
            'tipe_layanan' => 'lepas_kunci',
            'foto_ktp' => \Illuminate\Http\UploadedFile::fake()->image('ktp.jpg'),
        ]);

        $response2->assertStatus(200);
        $this->assertDatabaseHas('booking', [
            'id_mobil' => $mobilId,
            'id_user' => $pelangganId,
        ]);
    }

    /**
     * TC-2: Overlapping booking fails
     */
    public function test_booking_overlap_fails(): void
    {
        $pelangganId = DB::table('user')->insertGetId([
            'id_role' => 2,
            'nama' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
            'alamat' => 'Jl. Jalan 2',
            'no_telepon' => '081234567892',
        ]);

        $pelanggan = User::find($pelangganId);

        DB::table('verifikasi_akun')->insert([
            'id_user' => $pelangganId,
            'status' => 'verified',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $mobilId = $this->createCar('Civic', 'B 9999 CIV');

        $this->actingAs($pelanggan);

        // Book dates 1-3
        // Need to change the status in DB to simulate "active/booked" so that isCarAvailable detects it
        // The isCarAvailable method checks for statuses: ['menunggu_approval', 'menunggu', 'dibayar', 'disewakan']
        $bookingId = DB::table('booking')->insertGetId([
            'id_user' => $pelangganId,
            'id_mobil' => $mobilId,
            'tanggal_mulai' => now()->addDays(1)->format('Y-m-d 09:00:00'),
            'tanggal_selesai' => now()->addDays(3)->format('Y-m-d 18:00:00'),
            'waktu_mulai' => '09:00',
            'waktu_selesai' => '18:00:00',
            'status' => 'menunggu_approval',
            'tipe_layanan' => 'lepas_kunci',
        ]);

        // Try booking overlapping dates 2-4
        $response = $this->post(route('pelanggan.order.checkout.proses'), [
            'id_mobil' => $mobilId,
            'waktu_mulai' => '09:00',
            'waktu_selesai' => '18:00',
            'tanggal_mulai' => now()->addDays(2)->format('Y-m-d'),
            'tanggal_selesai' => now()->addDays(4)->format('Y-m-d'),
            'tipe_layanan' => 'lepas_kunci',
            'foto_ktp' => \Illuminate\Http\UploadedFile::fake()->image('ktp.jpg'),
        ]);

        $response->assertSessionHas('error');
    }

    /**
     * TC-5, 6: Verification Flow (Upload and Admin approve)
     */
    public function test_verification_flow(): void
    {
        $pelangganId = DB::table('user')->insertGetId([
            'id_role' => 2,
            'nama' => 'Bob Smith',
            'email' => 'bob@example.com',
            'password' => Hash::make('password123'),
            'alamat' => 'Jl. Vertikal',
            'no_telepon' => '081234567899',
        ]);

        $pelanggan = User::find($pelangganId);
        $this->actingAs($pelanggan);

        // Upload verification files
        $response = $this->post(route('pelanggan.verifikasi.upload'), [
            'foto_ktp' => \Illuminate\Http\UploadedFile::fake()->image('ktp.jpg'),
            'foto_sim' => \Illuminate\Http\UploadedFile::fake()->image('sim.jpg'),
            'foto_selfie' => \Illuminate\Http\UploadedFile::fake()->image('selfie.jpg'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('verifikasi_akun', [
            'id_user' => $pelangganId,
            'status' => 'pending',
        ]);

        $verification = VerifikasiAkun::where('id_user', $pelangganId)->first();

        // Admin Action
        $admin = User::find($this->adminId);
        $this->actingAs($admin);

        // Approve
        $approveResponse = $this->post(route('admin.verifikasi.approve', ['id' => $verification->id]));
        $approveResponse->assertRedirect();

        $this->assertDatabaseHas('verifikasi_akun', [
            'id_user' => $pelangganId,
            'status' => 'verified',
        ]);
    }
}
