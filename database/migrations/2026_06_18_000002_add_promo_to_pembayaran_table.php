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
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->integer('id_promo')->nullable()->after('id_booking');
            $table->decimal('potongan_harga', 10, 2)->default(0.00)->after('total_pembayaran');

            $table->foreign('id_promo')->references('id')->on('promo')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropForeign(['id_promo']);
            $table->dropColumn(['id_promo', 'potongan_harga']);
        });
    }
};
