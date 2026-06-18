<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pencairan_komisi', function (Blueprint $table) {
            $table->string('bukti_transfer', 255)->nullable()->change();
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending')->after('jumlah');
            $table->string('nama_bank', 100)->nullable()->after('status');
            $table->string('nomor_rekening', 50)->nullable()->after('nama_bank');
            $table->string('nama_rekening', 150)->nullable()->after('nomor_rekening');
            $table->string('catatan_admin', 255)->nullable()->after('catatan');
        });

        // Set status pencairan lama sebagai 'disetujui'
        \DB::table('pencairan_komisi')->update(['status' => 'disetujui']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pencairan_komisi', function (Blueprint $table) {
            $table->dropColumn(['status', 'nama_bank', 'nomor_rekening', 'nama_rekening', 'catatan_admin']);
            $table->string('bukti_transfer', 255)->nullable(false)->change();
        });
    }
};
