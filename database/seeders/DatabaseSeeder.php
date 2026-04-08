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
        // 1. Kosongkan tabel agar tidak terjadi duplikat data jika seeder dijalankan berulang
        // Gunakan statement agar foreign key tidak memblokir proses truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('user')->truncate();
        DB::table('role')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Insert Data Role (Wajib ada sebelum User dibuat)
        DB::table('role')->insert([
            ['id' => 1, 'nama_role' => 'admin'],
            ['id' => 2, 'nama_role' => 'pelanggan'],
            ['id' => 3, 'nama_role' => 'pemilik'],
        ]);

        // 3. Insert Data Admin Utama
        DB::table('user')->insert([
            'id_role' => 1, // Mengacu ke role 'admin'
            'nama' => 'Admin Utama',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'), // Default password: admin123
            'alamat' => 'Kantor Pusat Roda Kita, Jl. Merdeka No. 45',
            'no_telepon' => '081234567890',
        ]);

        $this->command->info('Data Role dan Admin Utama berhasil ditambahkan!');
    }
}