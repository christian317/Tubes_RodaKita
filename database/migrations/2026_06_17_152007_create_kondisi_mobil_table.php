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

        Schema::create('kondisi_mobil', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_booking');
            $table->enum('tipe', ['pengambilan', 'pengembalian']);
            $table->integer('odometer');
            $table->string('indikator_bensin', 45);
            $table->decimal('denda', 10, 2)->default(0.00)->nullable();
            $table->text('catatan')->nullable();
            $table->text('foto_kendaraan');
            $table->text('kondisi_eksterior');
            $table->text('kondisi_interior');
            $table->timestamps();
            
            $table->foreign('id_booking')->references('id')->on('booking')->onDelete('cascade');
        });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kondisi_mobil');
    }
};
