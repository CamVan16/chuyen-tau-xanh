<?php


namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Exchange extends Model
{
    use CrudTrait;
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
        'exchange_time',
        'exchange_time_processed',
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

        static::updated(function ($exchange) {
            if ($exchange->isDirty('exchange_status') && $exchange->exchange_status === 'completed') {
                $exchange->restoreTicket();
            }
        });
    }

    public function restoreTicket()
    {
        $ticket = Ticket::where('exchange_id', $this->id)->first();
        Ticket::create([
            'schedule_id' => $ticket->schedule_id,
            'price'       => $ticket->price,
        ]);
    }
}
