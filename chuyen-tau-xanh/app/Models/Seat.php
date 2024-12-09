<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $table = 'seats';

    protected $primaryKey = 'id';

    protected $fillable = [
        'car_id',
        'seat_type_id',
        'seat_type',
        'seat_index',
        'seat_status'
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function seatType()
    {
        return $this->belongsTo(SeatType::class, 'seat_type_id');
    }
}
