<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Refund extends Model
{
    use HasFactory;


    protected $fillable = [
        'booking_id',
        'refund_status',
        'refund_date',
        'customer_id',
        'payment_method',
        'refund_amount',
        'refund_date_processed',
    ];


    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }


    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
