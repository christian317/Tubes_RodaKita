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

        Schema::create('pemilik_mobil', function (Blueprint $table) {
            $table->integer('id_user')->primary();
            $table->string('nama_bank', 50);
            $table->string('nomor_rekening', 50);
            $table->string('nomor_ktp', 50)->unique();
            
            $table->foreign('id_user')->references('id')->on('user');
        });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemilik_mobil');
    }
};
