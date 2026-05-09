<?php
// app/Models/Review.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    // Daftarkan kolom sesuai dengan migrasi riil di Supabase lo
    protected $fillable = [
        'order_id', 
        'tukang_id', 
        'user_name', 
        'rating', 
        'comment'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}