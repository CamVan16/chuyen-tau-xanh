<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use CrudTrait;
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
