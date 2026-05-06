<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->text('bio')->nullable();
            $table->json('skills')->nullable(); // Disimpan sebagai array JSON
            $table->integer('price_kunjungan')->default(75000);
            $table->boolean('is_available')->default(true);
            $table->double('distance_km')->nullable();
            $table->json('schedule')->nullable(); // Contoh: {"Senin-Jumat": "08:00-17:00"}
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
