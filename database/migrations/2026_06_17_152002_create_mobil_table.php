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

        Schema::create('mobil', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_brand');
            $table->integer('id_kategori');
            $table->integer('id_pemilik_mobil');
            $table->string('model', 100);
            $table->string('plat_nomer', 20);
            $table->decimal('harga_sewa', 10, 2);
            $table->string('transmisi', 50);
            $table->integer('kapasitas_penumpang');
            $table->integer('tahun');
            $table->integer('status_katalog');
            $table->string('status_mobil', 20);
            $table->string('gambar', 255);
            $table->string('deskripsi', 255)->nullable();

            $table->foreign('id_brand')->references('id')->on('brand');
            $table->foreign('id_kategori')->references('id')->on('kategori');
            $table->foreign('id_pemilik_mobil')->references('id_user')->on('pemilik_mobil');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobil');
    }
};
