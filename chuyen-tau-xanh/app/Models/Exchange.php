<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Exchange extends Model
{
    use HasFactory;


    protected $fillable = [
        'exchange_id',
        'old_ticket_id',
        'new_ticket_id',
        'booking_id',
        'customer_id',
        'payment_method',
        'old_price',
        'new_price',
        'additional_price',
        'exchange_status',
        'exchange_date',
        'exchange_date_processed',
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
