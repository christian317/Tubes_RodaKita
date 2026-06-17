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

        Schema::create('pencairan_komisi', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_pemilik_mobil');
            $table->decimal('jumlah', 15, 2);
            $table->string('bukti_transfer', 255);
            $table->string('catatan', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_pemilik_mobil')->references('id_user')->on('pemilik_mobil')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pencairan_komisi');
    }
};
