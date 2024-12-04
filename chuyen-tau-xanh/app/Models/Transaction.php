<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $fillable = ['Name', 'Phone', 'Address', 'Status', 'Payment_Method', 'Note'];
    protected $primaryKey = 'Transaction_ID';
    public function orders()
    {
        //return $this->hasMany(Order::class, 'Transaction_ID', 'Transaction_ID');
    }
    public function user()
    {
        //return $this->belongsTo(User::class, 'User_ID', 'User_ID');
    }
    use HasFactory;
}
