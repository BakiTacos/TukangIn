<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id', 'label', 'receiver_name', 'phone_number', 
        'full_address', 'postal_code', 'note', 'is_primary'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
