<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StationArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'stationCode',
        'stationName',
        'km',
    ];
}
