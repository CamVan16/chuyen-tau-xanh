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
}
