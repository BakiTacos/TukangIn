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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Info Dasar
            $table->string('label'); // Contoh: Rumah, Kantor, Toko
            $table->string('receiver_name');
            $table->string('phone_number');

            // Hierarki Wilayah (Standar Indonesia)
            $table->string('province');      // Provinsi
            $table->string('city');          // Kota/Kabupaten
            $table->string('district');      // Kecamatan
            $table->string('village');       // Kelurahan/Desa
            $table->string('postal_code', 10);
            
            // Detail Lokasi
            $table->text('full_address');    // Nama jalan, No Rumah, RT/RW
            $table->string('note')->nullable(); // Patokan (misal: Depan gerbang putih)

            // Status
            $table->boolean('is_primary')->default(false); 
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