<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Train;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'train_id',
        'train_mark',
        'day_start',
        'time_start',
        'day_end',
        'time_end',
        'station_start',
        'station_end',
        'seat_number',
        'car_name',
    ];

    public function ticket()
    {
        return $this->hasMany(Booking::class, 'schedule_id');
    }

    public function train()
    {
        return $this->belongsTo(Train::class, 'train_id');
    }
}
