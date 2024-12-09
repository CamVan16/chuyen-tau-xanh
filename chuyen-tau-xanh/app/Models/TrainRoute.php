<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrainRoute extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $primaryKey = ['train_id', 'route_id'];
    protected $keyType = 'int';

    protected $table = 'train_routes';

    protected $fillable = ['train_id', 'route_id', 'train_index'];

    public function train()
    {
        return $this->belongsTo(Train::class, 'train_id');
    }

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }
}
