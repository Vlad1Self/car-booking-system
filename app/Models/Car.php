<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'license_plate',
        'color',
        'year',
        'car_model_id',
        'driver_id',
        'is_active',
        'features'
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
    ];

    public function carModel()
    {
        return $this->belongsTo(CarModel::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->carModel->brand} {$this->carModel->name} ({$this->license_plate})";
    }

    public function scopeAvailableBetween($query, $startTime, $endTime)
    {
        return $query->whereDoesntHave('trips', function ($q) use ($startTime, $endTime) {
            $q->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })->whereIn('status', ['planned', 'in_progress']);
        });
    }

    public function scopeAvailableForUser($query, $user)
    {
        if (!$user->position || $user->availableComfortCategories->isEmpty()) {
            return $query->whereRaw('1 = 0');
        }

        $availableCategoryIds = $user->availableComfortCategories->pluck('id');

        return $query->whereHas('carModel', function ($q) use ($availableCategoryIds) {
            $q->whereIn('comfort_category_id', $availableCategoryIds);
        });
    }
}
