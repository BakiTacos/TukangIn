<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Menambahkan kolom snapshot harga agar terkunci selamanya saat checkout
            $table->integer('service_fee')->default(0)->after('status');
            $table->integer('technician_fee')->default(0)->after('service_fee');
            $table->integer('tax_amount')->default(0)->after('technician_fee');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['service_fee', 'technician_fee', 'tax_amount']);
        });
    }
};