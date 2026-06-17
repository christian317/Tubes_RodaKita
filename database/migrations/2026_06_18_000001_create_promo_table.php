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
        Schema::create('promo', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('kode_promo', 50)->unique();
            $table->enum('tipe_potongan', ['persen', 'nominal']);
            $table->decimal('nominal_potongan', 15, 2);
            $table->decimal('minimal_transaksi', 15, 2)->default(0.00);
            $table->integer('kuota')->default(100);
            $table->date('tanggal_kadaluarsa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo');
    }
};
