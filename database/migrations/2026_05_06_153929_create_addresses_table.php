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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi ke tabel users
            $table->string('label'); // Contoh: Rumah, Kantor, Toko
            $table->string('receiver_name');
            $table->string('phone_number');
            $table->text('full_address');
            $table->string('postal_code', 10);
            $table->string('note')->nullable(); // Patokan lokasi
            $table->boolean('is_primary')->default(false); // Penanda alamat utama
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
