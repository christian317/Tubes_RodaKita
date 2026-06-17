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

        Schema::create('user', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_role');
            $table->string('nama', 255);
            $table->string('email', 255);
            $table->string('password', 255);
            $table->string('alamat', 500);
            $table->string('no_telepon', 15);
            
            $table->foreign('id_role')->references('id')->on('role');
        });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
