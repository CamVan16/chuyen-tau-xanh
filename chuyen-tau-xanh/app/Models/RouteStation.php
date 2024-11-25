<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteStation extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'station_code',
        'station_name',
        'km',
        'date_index',
        'departure_time',
        'arrival_time',
        'departure_date',
    ];
}
