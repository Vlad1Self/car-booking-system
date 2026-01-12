<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'license_plate' => $this->license_plate,
            'full_name' => $this->full_name,
            'color' => $this->color,
            'year' => $this->year,
            'features' => $this->features,
            'driver' => [
                'id' => $this->driver->id,
                'name' => $this->driver->name,
                'phone' => $this->driver->phone,
            ],
            'car_model' => [
                'id' => $this->carModel->id,
                'name' => $this->carModel->name,
                'brand' => $this->carModel->brand,
                'comfort_category' => [
                    'id' => $this->carModel->comfortCategory->id,
                    'name' => $this->carModel->comfortCategory->name,
                    'level' => $this->carModel->comfortCategory->level,
                ]
            ]
        ];
    }
}
