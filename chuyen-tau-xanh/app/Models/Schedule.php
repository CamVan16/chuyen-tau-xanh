<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
