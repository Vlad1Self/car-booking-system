<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'brand', 'comfort_category_id'];

    public function comfortCategory()
    {
        return $this->belongsTo(ComfortCategory::class);
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
