<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_name',
        'train_mark',
    ];

    public function stations()
    {
        return $this->belongsToMany(StationArea::class, 'route_stations', 'route_id', 'station_id')
                    ->withPivot('station_code', 'station_name', 'km', 'date_index', 'departure_time', 'arrival_time', 'departure_date')
                    ->withTimestamps();
    }

    public function trains()
    {
        return $this->belongsToMany(Train::class, 'train_routes', 'route_id', 'train_id')
                    ->withPivot('train_index')
                    ->withTimestamps();
    }
}
