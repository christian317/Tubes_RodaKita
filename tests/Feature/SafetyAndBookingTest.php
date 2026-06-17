<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VerifikasiAkun;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

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
            'foto_ktp' => UploadedFile::fake()->image('ktp.jpg'),
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
            'foto_ktp' => UploadedFile::fake()->image('ktp.jpg'),
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
            'foto_ktp' => UploadedFile::fake()->image('ktp.jpg'),
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
            'foto_ktp' => UploadedFile::fake()->image('ktp.jpg'),
            'foto_sim' => UploadedFile::fake()->image('sim.jpg'),
            'foto_selfie' => UploadedFile::fake()->image('selfie.jpg'),
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

    /**
     * Test customers applying a valid promo code
     */
    public function test_pelanggan_can_apply_valid_promo_code(): void
    {
        $promoId = DB::table('promo')->insertGetId([
            'kode_promo' => 'KITAHEBAT',
            'tipe_potongan' => 'persen',
            'nominal_potongan' => 10.00,
            'minimal_transaksi' => 100000.00,
            'kuota' => 5,
            'tanggal_kadaluarsa' => now()->addDays(5)->format('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $pelangganId = DB::table('user')->insertGetId([
            'id_role' => 2,
            'nama' => 'Promo User',
            'email' => 'promouser@example.com',
            'password' => Hash::make('password123'),
            'alamat' => 'Jl. Promo',
            'no_telepon' => '081234567800',
        ]);

        $pelanggan = User::find($pelangganId);
        $this->actingAs($pelanggan);

        DB::table('verifikasi_akun')->insert([
            'id_user' => $pelangganId,
            'status' => 'verified',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $mobilId = $this->createCar('Xenia', 'B 4321 XYZ');

        $response = $this->post(route('pelanggan.order.checkout.proses'), [
            'id_mobil' => $mobilId,
            'waktu_mulai' => '09:00',
            'waktu_selesai' => '09:00',
            'tanggal_mulai' => now()->addDays(1)->format('Y-m-d'),
            'tanggal_selesai' => now()->addDays(3)->format('Y-m-d'), // 2 Days = Rp 600.000
            'tipe_layanan' => 'lepas_kunci',
            'foto_ktp' => UploadedFile::fake()->image('ktp.jpg'),
            'applied_id_promo' => $promoId,
        ]);

        $response->assertStatus(200);

        // Assert discount is 10% of 600,000 = 60,000
        // Total payment should be 540,000
        // Owner commission (70% of 540,000) = 378,000
        $this->assertDatabaseHas('pembayaran', [
            'id_promo' => $promoId,
            'potongan_harga' => 60000.00,
            'total_pembayaran' => 540000.00,
            'komisi_pemilik' => 378000.00,
        ]);

        // Quota should decrement to 4
        $this->assertDatabaseHas('promo', [
            'id' => $promoId,
            'kuota' => 4,
        ]);
    }

    /**
     * Test customers cannot apply expired or empty-quota promo code
     */
    public function test_pelanggan_cannot_apply_expired_or_invalid_promo_code(): void
    {
        $expiredPromoId = DB::table('promo')->insertGetId([
            'kode_promo' => 'KADALUARSA',
            'tipe_potongan' => 'nominal',
            'nominal_potongan' => 50000.00,
            'minimal_transaksi' => 100000.00,
            'kuota' => 5,
            'tanggal_kadaluarsa' => now()->subDays(2)->format('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $pelangganId = DB::table('user')->insertGetId([
            'id_role' => 2,
            'nama' => 'Promo User 2',
            'email' => 'promouser2@example.com',
            'password' => Hash::make('password123'),
            'alamat' => 'Jl. Promo 2',
            'no_telepon' => '081234567801',
        ]);

        $pelanggan = User::find($pelangganId);
        $this->actingAs($pelanggan);

        DB::table('verifikasi_akun')->insert([
            'id_user' => $pelangganId,
            'status' => 'verified',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $mobilId = $this->createCar('Ayla', 'B 5555 AY');

        $response = $this->post(route('pelanggan.order.checkout.proses'), [
            'id_mobil' => $mobilId,
            'waktu_mulai' => '09:00',
            'waktu_selesai' => '09:00',
            'tanggal_mulai' => now()->addDays(1)->format('Y-m-d'),
            'tanggal_selesai' => now()->addDays(3)->format('Y-m-d'), // 2 Days = Rp 600.000
            'tipe_layanan' => 'lepas_kunci',
            'foto_ktp' => UploadedFile::fake()->image('ktp.jpg'),
            'applied_id_promo' => $expiredPromoId,
        ]);

        $response->assertStatus(200);

        // Expired promo should NOT be applied (potongan_harga = 0, total = 600,000)
        $this->assertDatabaseHas('pembayaran', [
            'id_promo' => null,
            'potongan_harga' => 0.00,
            'total_pembayaran' => 600000.00,
        ]);
    }

    /**
     * Test admin can create a car with coordinates and pickup address
     */
    public function test_car_coordinates_stored_correctly_on_creation(): void
    {
        $admin = User::find($this->adminId);
        $this->actingAs($admin);

        $brandId = DB::table('brand')->insertGetId(['nama_brand' => 'Honda']);
        $kategoriId = DB::table('kategori')->insertGetId(['nama_kategori' => 'Sedan']);

        $response = $this->post(route('admin.mobil.store'), [
            'id_brand' => $brandId,
            'id_kategori' => $kategoriId,
            'id_pemilik_mobil' => $this->mitraId,
            'model' => 'Civic Turbo',
            'plat_nomer' => 'D 9999 CC',
            'harga_sewa' => 500000,
            'transmisi' => 'Automatic',
            'kapasitas_penumpang' => 5,
            'tahun' => 2023,
            'status_mobil' => 'tersedia',
            'gambar' => UploadedFile::fake()->image('civic.jpg'),
            'deskripsi' => 'Kencang dan nyaman',
            'latitude' => -7.250445,
            'longitude' => 112.768845,
            'alamat_jemput' => 'Surabaya, Jawa Timur',
        ]);

        $response->assertRedirect(route('admin.mobil.index'));

        $this->assertDatabaseHas('mobil', [
            'model' => 'Civic Turbo',
            'latitude' => -7.250445,
            'longitude' => 112.768845,
            'alamat_jemput' => 'Surabaya, Jawa Timur',
        ]);
    }

    /**
     * Test admin can update a car with coordinates and pickup address
     */
    public function test_car_coordinates_stored_correctly_on_update(): void
    {
        $admin = User::find($this->adminId);
        $this->actingAs($admin);

        $mobilId = $this->createCar('Brio', 'D 1111 BB');

        $response = $this->put(route('admin.mobil.update', ['id' => $mobilId]), [
            'id_brand' => DB::table('mobil')->where('id', $mobilId)->value('id_brand'),
            'id_kategori' => DB::table('mobil')->where('id', $mobilId)->value('id_kategori'),
            'id_pemilik_mobil' => $this->mitraId,
            'model' => 'Brio RS',
            'plat_nomer' => 'D 1111 BB',
            'harga_sewa' => 250000,
            'transmisi' => 'Manual',
            'kapasitas_penumpang' => 5,
            'tahun' => 2021,
            'status_mobil' => 'tersedia',
            'deskripsi' => 'Irit sekali',
            'latitude' => -6.914744,
            'longitude' => 107.609810,
            'alamat_jemput' => 'Bandung, Jawa Barat',
        ]);

        $response->assertRedirect(route('admin.mobil.index'));

        $this->assertDatabaseHas('mobil', [
            'id' => $mobilId,
            'model' => 'Brio RS',
            'latitude' => -6.914744,
            'longitude' => 107.609810,
            'alamat_jemput' => 'Bandung, Jawa Barat',
        ]);
    }
}
