<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
    'order_number',
    'user_id',
    'service_id',
    'tukang_id',
    'address_id',
    'payment_method',
    'payment_bank',
    'promo_code',
    'discount_amount',
    'total_cost',
    'status',
    'schedule_date',
    
    // SESUAIKAN TIGA SNAPSHOT INI DENGAN KOLOM SUPABASE KAMU:
    'service_fee',
    'technician_fee',
    'tax_amount',
    'platform_fee', // <--- UBAH DARI 'payment_fee' MENJADI 'platform_fee'
    'completion_photo',
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