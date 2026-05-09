<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'tukang_id', 'service_id', 
        'address_id', 'schedule_date', 'status', 'total_cost', 'problem_description',
        // TAMBAHKAN DUA KOLOM INI AGAR BISA DISIMPAN VIA MASS ASSIGNMENT
        'complaint_reason',
        'complaint_description',
        // DAFTARKAN KEDUA KOLOM BARU INI (BARU)
        'cancel_reason',
        'cancel_description',
        
    ];

    protected $casts = [
        'schedule_date' => 'datetime',
    ];

    // app/Models/Order.php

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    // Relasi ke Pelanggan
    public function user() { return $this->belongsTo(User::class, 'user_id'); }
    
    // Relasi ke Tukang
    public function tukang() { return $this->belongsTo(User::class, 'tukang_id'); }
    
    // Relasi ke Layanan
    public function service() { return $this->belongsTo(Service::class); }
    
    // Relasi ke Alamat
    public function address() { return $this->belongsTo(Address::class); }
}