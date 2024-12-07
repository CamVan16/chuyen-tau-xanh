<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ExchangePolicy extends Model
{
    use HasFactory;


    protected $fillable = [
        'min_hours',
        'max_hours',
        'exchange_fee',
    ];
}