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

        Schema::create('ulasan', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_booking');
            $table->enum('tipe', ['mobil', 'pelanggan']);
            $table->integer('rating');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_booking')->references('id')->on('booking')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};
