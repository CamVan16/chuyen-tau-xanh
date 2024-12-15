<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StationArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'station_code',
        'station_name',
        'km',
    ];

    public function routes()
    {
        return $this->belongsToMany(Route::class, 'route_stations', 'station_id', 'route_id')
                    ->withPivot('station_code', 'station_name', 'km', 'date_index', 'departure_time', 'arrival_time', 'departure_date')
                    ->withTimestamps();
    }
}
