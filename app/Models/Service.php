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

// app/Models/Service.php

/**
 * Accessor untuk memastikan harga selalu dibaca sebagai integer murni.
 * Mengubah string '200.000' otomatis menjadi integer 200000.
 */
public function getPriceAttribute($value)
{
    if (is_string($value)) {
        return (int) str_replace('.', '', $value);
    }
    return (int) $value;
}
    //
}
