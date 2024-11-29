<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ticket;

class Booking extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'customer_id',
        'discount_price',
        'booked_time',
        'booking_status',
        'total_price',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = strtoupper(bin2hex(random_bytes(3)));
            }
        });
    }

    public function refunds()
    {
        return $this->hasMany(Refund::class,'booking_id');
    }

    public function exchanges()
    {
        return $this->hasMany(Exchange::class,'booking_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'booking_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
