<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
<<<<<<< HEAD
=======

>>>>>>> 51dd944405a04f3ce4b5574a36700b43ded003e0
    protected $fillable = [
        'customer_name',
        'customer_type',
        'email',
<<<<<<< HEAD
        'citizenID',
=======
        'citizen_id',
>>>>>>> 51dd944405a04f3ce4b5574a36700b43ded003e0
        'phone',
    ];
}
