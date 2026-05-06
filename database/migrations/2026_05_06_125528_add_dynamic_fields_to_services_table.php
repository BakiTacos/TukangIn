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
    Schema::table('services', function (Blueprint $table) {
        // boolean untuk kontrol dropdown kapasitas di sidebar
        $table->boolean('has_capacity')->default(false)->after('price');
        // JSON untuk menyimpan daftar fitur (icon, title, desc)
        $table->json('inclusions')->nullable()->after('has_capacity');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            //
        });
    }
};
