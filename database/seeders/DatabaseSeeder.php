<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Clear tables in reverse dependency order to avoid constraint issues
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('klaim_asuransi')->truncate();
        DB::table('verifikasi_akun')->truncate();
        DB::table('pembayaran')->truncate();
        DB::table('booking')->truncate();
        DB::table('mobil')->truncate();
        DB::table('pemilik_mobil')->truncate();
        DB::table('kategori')->truncate();
        DB::table('brand')->truncate();
        DB::table('user')->truncate();
        DB::table('role')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Seed Roles
        DB::table('role')->insert([
            ['id' => 1, 'nama_role' => 'admin'],
            ['id' => 2, 'nama_role' => 'pelanggan'],
            ['id' => 3, 'nama_role' => 'pemilik'],
        ]);

        // 3. Seed Users
        // Admin
        DB::table('user')->insert([
            'id' => 1,
            'id_role' => 1,
            'nama' => 'Admin Utama',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'alamat' => 'Kantor Pusat Roda Kita, Jl. Merdeka No. 45',
            'no_telepon' => '081234567890',
        ]);

        // Pelanggan 1 (Verified)
        $pelanggan1Id = DB::table('user')->insertGetId([
            'id_role' => 2,
            'nama' => 'Budi Santoso',
            'email' => 'pelanggan@gmail.com',
            'password' => Hash::make('password123'),
            'alamat' => 'Jl. Mawar No. 12, Jakarta',
            'no_telepon' => '082234567891',
        ]);

        // Pelanggan 2 (Unverified)
        $pelanggan2Id = DB::table('user')->insertGetId([
            'id_role' => 2,
            'nama' => 'Adit Pratama',
            'email' => 'unverified@gmail.com',
            'password' => Hash::make('password123'),
            'alamat' => 'Jl. Melati No. 4, Bandung',
            'no_telepon' => '082234567892',
        ]);

        // Pelanggan 3 (Pending Verification)
        $pelanggan3Id = DB::table('user')->insertGetId([
            'id_role' => 2,
            'nama' => 'Citra Lestari',
            'email' => 'pending@gmail.com',
            'password' => Hash::make('password123'),
            'alamat' => 'Jl. Anggrek No. 88, Surabaya',
            'no_telepon' => '082234567893',
        ]);

        // Mitra / Pemilik 1
        $mitra1Id = DB::table('user')->insertGetId([
            'id_role' => 3,
            'nama' => 'Hendra Wijaya',
            'email' => 'mitra@gmail.com',
            'password' => Hash::make('password123'),
            'alamat' => 'Ruko Permata Hijau Blok C-12, Jakarta',
            'no_telepon' => '083334567891',
        ]);

        // Mitra / Pemilik 2
        $mitra2Id = DB::table('user')->insertGetId([
            'id_role' => 3,
            'nama' => 'Siti Aminah',
            'email' => 'mitra2@gmail.com',
            'password' => Hash::make('password123'),
            'alamat' => 'Jl. Kemuning No. 15, Jogja',
            'no_telepon' => '083334567892',
        ]);

        // 4. Seed PemilikMobil records (Mitras need this to own cars)
        DB::table('pemilik_mobil')->insert([
            [
                'id_user' => $mitra1Id,
                'nama_bank' => 'BCA',
                'nomor_rekening' => '1234567890',
                'nomor_ktp' => '3171012345670001',
            ],
            [
                'id_user' => $mitra2Id,
                'nama_bank' => 'Mandiri',
                'nomor_rekening' => '0987654321',
                'nomor_ktp' => '3171012345670002',
            ],
        ]);

        // 5. Seed VerifikasiAkun records
        DB::table('verifikasi_akun')->insert([
            // Pelanggan 1 is fully verified
            [
                'id_user' => $pelanggan1Id,
                'foto_ktp' => 'ktp_budi.jpg',
                'foto_sim' => 'sim_budi.jpg',
                'foto_selfie' => 'selfie_budi.jpg',
                'status' => 'verified',
                'catatan_verifikasi' => 'Data KTP & SIM lengkap.',
                'verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Pelanggan 3 is pending verifikasi
            [
                'id_user' => $pelanggan3Id,
                'foto_ktp' => 'ktp_citra.jpg',
                'foto_sim' => 'sim_citra.jpg',
                'foto_selfie' => 'selfie_citra.jpg',
                'status' => 'pending',
                'catatan_verifikasi' => null,
                'verified_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 6. Seed Brands
        $toyotaId = DB::table('brand')->insertGetId(['nama_brand' => 'Toyota']);
        $hondaId = DB::table('brand')->insertGetId(['nama_brand' => 'Honda']);
        $hyundaiId = DB::table('brand')->insertGetId(['nama_brand' => 'Hyundai']);
        $suzukiId = DB::table('brand')->insertGetId(['nama_brand' => 'Suzuki']);

        // 7. Seed Kategori
        $mpvId = DB::table('kategori')->insertGetId(['nama_kategori' => 'MPV']);
        $sedanId = DB::table('kategori')->insertGetId(['nama_kategori' => 'Sedan']);
        $suvId = DB::table('kategori')->insertGetId(['nama_kategori' => 'SUV']);
        $hatchbackId = DB::table('kategori')->insertGetId(['nama_kategori' => 'Hatchback']);

        // 8. Seed Mobil
        $mobil1Id = DB::table('mobil')->insertGetId([
            'id_brand' => $toyotaId,
            'id_kategori' => $mpvId,
            'id_pemilik_mobil' => $mitra1Id,
            'model' => 'Avanza Veloz',
            'plat_nomer' => 'B 1234 SKA',
            'harga_sewa' => 350000.00,
            'transmisi' => 'Automatic',
            'kapasitas_penumpang' => 7,
            'tahun' => 2022,
            'status_katalog' => 1,
            'status_mobil' => 'tersedia',
            'gambar' => 'avanza_veloz.jpg',
            'deskripsi' => 'Mobil keluarga yang sangat nyaman, irit bahan bakar, dan dilengkapi AC double blower.',
            'alamat_jemput' => 'Ruko Permata Hijau Blok C-12, Kebayoran Lama, Jakarta Selatan',
            'latitude' => -6.221034,
            'longitude' => 106.779145,
        ]);

        $mobil2Id = DB::table('mobil')->insertGetId([
            'id_brand' => $hondaId,
            'id_kategori' => $sedanId,
            'id_pemilik_mobil' => $mitra1Id,
            'model' => 'Civic Turbo',
            'plat_nomer' => 'B 9999 CIV',
            'harga_sewa' => 750000.00,
            'transmisi' => 'Automatic',
            'kapasitas_penumpang' => 5,
            'tahun' => 2021,
            'status_katalog' => 1,
            'status_mobil' => 'tersedia',
            'gambar' => 'civic_turbo.jpg',
            'deskripsi' => 'Sedan sporty dengan performa mesin turbocharger yang bertenaga dan kabin mewah.',
            'alamat_jemput' => 'Ruko Permata Hijau Blok C-12, Kebayoran Lama, Jakarta Selatan',
            'latitude' => -6.221034,
            'longitude' => 106.779145,
        ]);

        $mobil3Id = DB::table('mobil')->insertGetId([
            'id_brand' => $hyundaiId,
            'id_kategori' => $suvId,
            'id_pemilik_mobil' => $mitra2Id,
            'model' => 'Creta Prime',
            'plat_nomer' => 'AB 5555 HY',
            'harga_sewa' => 500000.00,
            'transmisi' => 'Automatic',
            'kapasitas_penumpang' => 5,
            'tahun' => 2023,
            'status_katalog' => 1,
            'status_mobil' => 'tersedia',
            'gambar' => 'hyundai_creta.jpg',
            'deskripsi' => 'SUV modern dengan sunroof premium, Hyundai SmartSense, dan audio BOSE.',
            'alamat_jemput' => 'Jl. Kemuning No. 15, Danurejan, Yogyakarta',
            'latitude' => -7.795580,
            'longitude' => 110.369490,
        ]);

        // 9. Seed Booking & Pembayaran (TC-4 Status Flow)
        // Booking 1: Selesai (Completed)
        $booking1Id = DB::table('booking')->insertGetId([
            'id_user' => $pelanggan1Id,
            'id_mobil' => $mobil1Id,
            'tanggal_mulai' => now()->subDays(5)->format('Y-m-d H:i:s'),
            'tanggal_selesai' => now()->subDays(3)->format('Y-m-d H:i:s'),
            'waktu_mulai' => '09:00',
            'waktu_selesai' => '09:00:00',
            'status' => 'selesai',
            'tipe_layanan' => 'lepas_kunci',
            'foto_ktp' => 'ktp_budi.jpg',
        ]);

        DB::table('pembayaran')->insert([
            'id_booking' => $booking1Id,
            'total_pembayaran' => 700000.00,
            'status_pembayaran' => 'dibayar',
            'komisi_pemilik' => 630000.00, // 90%
        ]);

        // Booking 2: Aktif (Sedang disewa)
        $booking2Id = DB::table('booking')->insertGetId([
            'id_user' => $pelanggan1Id,
            'id_mobil' => $mobil2Id,
            'tanggal_mulai' => now()->subDays(1)->format('Y-m-d H:i:s'),
            'tanggal_selesai' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'waktu_mulai' => '10:00',
            'waktu_selesai' => '10:00:00',
            'status' => 'disewakan',
            'tipe_layanan' => 'lepas_kunci',
            'foto_ktp' => 'ktp_budi.jpg',
        ]);

        DB::table('pembayaran')->insert([
            'id_booking' => $booking2Id,
            'total_pembayaran' => 2250000.00,
            'status_pembayaran' => 'dibayar',
            'komisi_pemilik' => 2025000.00,
        ]);

        // Booking 3: Pending Pembayaran (Menunggu Pembambilan / Belum Diambil)
        $booking3Id = DB::table('booking')->insertGetId([
            'id_user' => $pelanggan1Id,
            'id_mobil' => $mobil3Id,
            'tanggal_mulai' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'tanggal_selesai' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'waktu_mulai' => '08:00',
            'waktu_selesai' => '08:00:00',
            'status' => 'menunggu',
            'tipe_layanan' => 'lepas_kunci',
            'foto_ktp' => 'ktp_budi.jpg',
        ]);

        DB::table('pembayaran')->insert([
            'id_booking' => $booking3Id,
            'total_pembayaran' => 1000000.00,
            'status_pembayaran' => 'belum_dibayar',
            'komisi_pemilik' => 900000.00,
        ]);

        // Booking 4: Dibatalkan (Cancelled)
        $booking4Id = DB::table('booking')->insertGetId([
            'id_user' => $pelanggan1Id,
            'id_mobil' => $mobil3Id,
            'tanggal_mulai' => now()->subDays(10)->format('Y-m-d H:i:s'),
            'tanggal_selesai' => now()->subDays(8)->format('Y-m-d H:i:s'),
            'waktu_mulai' => '08:00',
            'waktu_selesai' => '08:00:00',
            'status' => 'batal',
            'tipe_layanan' => 'lepas_kunci',
            'foto_ktp' => 'ktp_budi.jpg',
        ]);

        DB::table('pembayaran')->insert([
            'id_booking' => $booking4Id,
            'total_pembayaran' => 1000000.00,
            'status_pembayaran' => 'gagal',
            'komisi_pemilik' => 0.00,
        ]);

        // 10. Seed KlaimAsuransi
        DB::table('klaim_asuransi')->insert([
            // Claim 1: Approved
            [
                'id_booking' => $booking1Id,
                'id_pemilik_mobil' => $mitra1Id,
                'deskripsi_kerusakan' => 'Bemper depan lecet terserempet trotoar.',
                'estimasi_biaya' => 500000.00,
                'biaya_disetujui' => 450000.00,
                'foto_bukti' => json_encode(['bemper_lecet.jpg']),
                'status' => 'disetujui',
                'catatan_klaim' => 'Klaim disetujui sesuai estimasi bengkel rekanan.',
                'submitted_at' => now()->subDays(2),
                'processed_at' => now()->subDays(1),
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(1),
            ],
            [
                'id_booking' => $booking2Id,
                'id_pemilik_mobil' => $mitra1Id,
                'deskripsi_kerusakan' => 'Kaca spion kanan pecah.',
                'estimasi_biaya' => 300000.00,
                'biaya_disetujui' => null,
                'foto_bukti' => json_encode(['spion_pecah.jpg']),
                'status' => 'diajukan',
                'catatan_klaim' => null,
                'submitted_at' => now(),
                'processed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Generate dummy files in public storage to avoid 403/404 errors
        $dummyImage = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=');
        
        $filesToCreate = [
            'ktp_budi.jpg',
            'sim_budi.jpg',
            'selfie_budi.jpg',
            'ktp_citra.jpg',
            'sim_citra.jpg',
            'selfie_citra.jpg',
            'avanza_veloz.jpg',
            'civic_turbo.jpg',
            'hyundai_creta.jpg',
        ];

        foreach ($filesToCreate as $file) {
            \Illuminate\Support\Facades\Storage::disk('public')->put($file, $dummyImage);
        }

        $this->command->info('RodaKita dummy data successfully seeded!');
    }
}
