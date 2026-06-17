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

        Schema::create('verifikasi_akun', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_user');
            $table->string('foto_ktp', 255)->nullable();
            $table->string('foto_sim', 255)->nullable();
            $table->string('foto_selfie', 255)->nullable();
            $table->enum('status', ['unverified', 'pending', 'verified', 'rejected'])->default('unverified');
            $table->text('catatan_verifikasi')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->timestamps();
            
            $table->foreign('id_user')->references('id')->on('user')->onDelete('cascade');
        });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifikasi_akun');
    }
};
