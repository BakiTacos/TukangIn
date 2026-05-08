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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // Contoh: ORD-20261024-001
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Pelanggan
            $table->foreignId('tukang_id')->nullable()->constrained('users')->onDelete('set null'); // Teknisi (bisa null jika belum di-assign)
            $table->foreignId('service_id')->constrained()->onDelete('cascade'); // Layanan (AC, Listrik, dll)
            $table->foreignId('address_id')->constrained()->onDelete('cascade'); // Alamat pelanggan
            
            $table->dateTime('schedule_date');
            $table->enum('status', ['pending', 'pengerjaan', 'selesai', 'batal', 'dikomplain'])->default('pending');
            $table->integer('total_cost')->default(0);
            $table->text('problem_description')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
