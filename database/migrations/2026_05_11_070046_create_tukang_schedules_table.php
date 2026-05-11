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
    Schema::create('tukang_schedules', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->string('day'); // 'Senin', 'Selasa', 'Rabu', dst
        $table->boolean('is_active')->default(false); // Apakah hari tersebut aktif bekerja
        $table->time('start_time')->default('08:00'); // Jam mulai kerja
        $table->time('end_time')->default('17:00'); // Jam selesai kerja
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tukang_schedules');
    }
};
