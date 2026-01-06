<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'id',
        'model_id',
        'user_id',
        'price_per_day',
        'status',
        'year',
    ];
}
