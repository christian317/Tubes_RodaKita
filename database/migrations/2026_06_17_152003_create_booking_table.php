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

        Schema::create('booking', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_user');
            $table->integer('id_mobil');
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->string('waktu_mulai', 20);
            $table->time('waktu_selesai');
            $table->string('status', 20);
            $table->string('tipe_layanan', 50);
            $table->string('foto_ktp', 255)->nullable();
            
            $table->foreign('id_user')->references('id')->on('user');
            $table->foreign('id_mobil')->references('id')->on('mobil');
        });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
