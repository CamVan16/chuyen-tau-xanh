<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteStation extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $primaryKey = ['route_id', 'station_id'];
    
    protected $fillable = [
        'route_id',
        'station_id',
        'station_code',
        'station_name',
        'km',
        'date_index',
        'departure_time',
        'arrival_time',
        'departure_date',
    ];

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    public function station()
    {
        return $this->belongsTo(StationArea::class, 'station_id');
    }
}
