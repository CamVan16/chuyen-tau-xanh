<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Train;
use App\Models\Ticket;

class Schedule extends Model
{
    use CrudTrait;
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

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'schedule_id');
    }

    public function train()
    {
        return $this->belongsTo(Train::class, 'train_id');
    }
}
