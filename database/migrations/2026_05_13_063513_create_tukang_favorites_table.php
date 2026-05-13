<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tukang_favorites', function (Blueprint $table) {
            $table->id();
            // Menghubungkan user pelanggan ke tabel users
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Menghubungkan mitra teknisi ke tabel users
            $table->foreignId('tukang_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // ⚡ OPTIMASI: Mencegah user mem-favoritkan teknisi yang sama berulang kali
            $table->unique(['user_id', 'tukang_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tukang_favorites');
    }
};