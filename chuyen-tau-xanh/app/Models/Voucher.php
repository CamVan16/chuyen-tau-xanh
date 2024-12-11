<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'min_price_order',
        'percent',
        'max_price_discount',
        'type',
        'from_date',
        'to_date',
        'quantity',
        'description',
    ];
}
