<?php


namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Refund extends Model
{
    use HasFactory;
    use CrudTrait;


    protected $fillable = [
        'booking_id',
        'refund_status',
        'refund_time',
        'customer_id',
        'payment_method',
        'refund_amount',
        'refund_time_processed',
    ];


    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }


    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($refund) {
            if ($refund->isDirty('refund_status') && $refund->refund_status === 'completed') {
                $refund->restoreTicket();
            }
        });
    }

    public function restoreTicket()
    {
        $ticket = Ticket::where('refund_id', $this->id)->first();
        Ticket::create([
            'schedule_id' => $ticket->schedule_id,
            'price'       => $ticket->price,
        ]);
    }
}
