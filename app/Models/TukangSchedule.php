<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TukangSchedule extends Model
{
    protected $fillable = ['user_id', 'day', 'is_active', 'start_time', 'end_time'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}