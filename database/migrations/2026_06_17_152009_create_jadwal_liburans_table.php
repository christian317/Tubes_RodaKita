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

        Schema::create('jadwal_liburans', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_booking');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('kegiatan', 255);
            $table->string('lokasi', 255)->nullable();
            $table->timestamps();
            
            $table->foreign('id_booking')->references('id')->on('booking')->onDelete('cascade');
        });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_liburans');
    }
};
