<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Train extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'num_of_seats',
        'num_of_available_seats',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'train_id');
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function seat_types()
    {
        return $this->hasMany(SeatType::class);
    }

    public function seats() {
        return $this->hasManyThrough(Seat::class, Car::class);
    }

    public function routes()
    {
        return $this->belongsToMany(Route::class, 'train_routes', 'train_id', 'route_id')
                    ->withPivot('train_index')
                    ->withTimestamps();
    }
}
