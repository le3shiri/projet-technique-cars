<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Models extends Model
{
    protected $table = 'models';
    
    protected $fillable = [
        'id',
        'name',
        'brand',
    ];

    public function cars()
    {
        return $this->hasMany(Car::class, 'model_id');
    }
}
