<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatType extends Model
{
    use HasFactory;

    protected $table = 'seat_types';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'train_id',
        'seat_type_code',
        'seat_type_name',
        'price'
    ];

    public function train()
    {
        return $this->belongsTo(Train::class, 'train_id');
    }

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
}
