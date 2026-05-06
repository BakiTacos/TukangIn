<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
    'title', 'slug', 'category', 'description', 'price', 
    'image', 'icon', 'color', 'has_capacity', 'inclusions'
];

protected $casts = [
    'inclusions' => 'array', // Casting otomatis JSON ke Array
];
    //
}
