<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'province',
    'city'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

        protected $casts = [
        'skills' => 'array',
        'schedule' => 'array',
    ];

public function reviews()
{
    // Review terhubung ke User (Tukang) lewat perantara model Order
    return $this->hasManyThrough(Review::class, Order::class, 'tukang_id', 'order_id');
}

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function getAverageRatingAttribute()
    {
        return round($this->reviews()->avg('rating'), 1) ?: 0;
    }

    public function orders()
{
    return $this->hasMany(Order::class, 'tukang_id');
}

public function completedOrders()
{
    return $this->hasMany(Order::class, 'tukang_id')->where('status', 'selesai');
}

    public function services()
    {
        return $this->hasMany(Service::class, 'category', 'category');
    }

    public function getPriceKunjunganAttribute($value)
    {
        if (is_string($value)) {
            return (int) str_replace('.', '', $value);
        }
        return (int) $value;
    }

    public function schedules()
{
    return $this->hasMany(\App\Models\TukangSchedule::class, 'user_id');
}
}
