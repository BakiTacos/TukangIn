<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
    'user_id', 
    'label', 
    'receiver_name', 
    'phone_number', 
    'province',      // Tambahkan ini
    'city',          // Tambahkan ini
    'district',      // Tambahkan ini
    'village',       // Tambahkan ini
    'postal_code', 
    'full_address', 
    'note', 
    'is_primary'
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
