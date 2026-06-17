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

        Schema::create('klaim_asuransi', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_booking');
            $table->integer('id_pemilik_mobil');
            $table->text('deskripsi_kerusakan');
            $table->decimal('estimasi_biaya', 15, 2);
            $table->decimal('biaya_disetujui', 15, 2)->nullable();
            $table->text('foto_bukti')->nullable(); // Using text for SQLite and JSON compatibility
            $table->enum('status', ['diajukan', 'ditinjau', 'disetujui', 'ditolak', 'selesai'])->default('diajukan');
            $table->text('catatan_klaim')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('processed_at')->nullable();
            $table->timestamps();

            $table->foreign('id_booking')->references('id')->on('booking');
            $table->foreign('id_pemilik_mobil')->references('id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('klaim_asuransi');
    }
};
