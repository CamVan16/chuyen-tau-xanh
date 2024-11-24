<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'seat_type_id',
        'seat_type',
        'seat_index',
        'seat_status',
    ];
}
