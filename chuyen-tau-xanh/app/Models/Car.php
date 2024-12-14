<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $table = 'cars';

    protected $primaryKey = 'id';

    protected $fillable = [
        'train_id',
        'car_index',
        'car_name',
        'car_code',
        'car_layout',
        'car_description',
        'num_of_seats',
        'num_of_available_seats'
    ];

    public function train()
    {
        return $this->belongsTo(Train::class);
    }

    public function seats()
    {
        return $this->hasMany(Seat::class, 'car_id');
    }
}
