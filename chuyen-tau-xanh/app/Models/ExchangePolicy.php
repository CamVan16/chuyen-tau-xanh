<?php


namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ExchangePolicy extends Model
{
    use CrudTrait;
    use HasFactory;


    protected $fillable = [
        'min_hours',
        'max_hours',
        'exchange_fee',
    ];
}
