<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;
use App\Models\Refund;
use App\Models\Customer;
use App\Models\Schedule;

class Ticket extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'booking_id',
        'customer_id',
        'refund_id',
        'exchange_id',
        'schedule_id',
        'price',
        'discount_price',
        'ticket_status'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = strtoupper(bin2hex(random_bytes(4)));
            }
        });
    }


    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }


    public function refund()
    {
        return $this->belongsTo(Refund::class, 'refund_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }
}
