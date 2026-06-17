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

        Schema::create('pembayaran', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_booking');
            $table->decimal('total_pembayaran', 10, 2);
            $table->string('status_pembayaran', 50);
            $table->decimal('komisi_pemilik', 10, 2);
            
            $table->foreign('id_booking')->references('id')->on('booking');
        });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
