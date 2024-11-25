<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'customer_id',
        'refund_id',
        'exchange_id',
        'schedule_id',
        'price',
        'discount_price',
    ];
    public function bookings()
    {
        return $this->belongsTo(Booking::class);
    }


    public function refund()
    {
        return $this->belongsTo(Refund::class, 'refund_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
