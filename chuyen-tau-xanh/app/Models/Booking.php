<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Attributes\Ticket;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'discount_price',
        'booked_time',
        'booking_status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = strtoupper(bin2hex(random_bytes(3)));
        });
    }



    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }


    public function tickets()
    {
        return $this->hasMany(Ticket::class,'booking_id','booking_id');
    }
}
