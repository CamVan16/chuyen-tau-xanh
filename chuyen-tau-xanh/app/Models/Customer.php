<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_type',
        'email',
        'citizen_id',
        'phone',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'customer_id');
    }

    public function tickets()
    {
        return $this->hasMany(Booking::class, 'customer_id');
    }
}
