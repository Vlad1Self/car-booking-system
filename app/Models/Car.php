<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailableBetween(Builder $query, $start, $end)
    {
        return $query->whereDoesntHave('trips', function ($q) use ($start, $end) {
            $q->where(function ($sub) use ($start, $end) {
                $sub->whereBetween('start_time', [$start, $end])
                    ->orWhereBetween('end_time', [$start, $end])
                    ->orWhere(fn($sq) => $sq->where('start_time', '<=', $start)->where('end_time', '>=', $end));
            })->whereIn('status', ['planned', 'in_progress']);
        });
    }

    public function scopeForUser(Builder $query, User $user)
    {
        return $query->whereHas('carModel', function ($q) use ($user) {
            $q->whereIn('comfort_category_id', $user->available_comfort_categories->pluck('id'));
        });
    }
}
