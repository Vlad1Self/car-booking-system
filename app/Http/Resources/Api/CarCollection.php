<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Api\CarResource;

class CarCollection extends ResourceCollection
{
    public $collects = CarResource::class;

    public function toArray(Request $request): array
    {
        return [
            'items' => $this->collection,
            'total' => $this->collection->count(),
            'timestamp' => now()->toDateTimeString(),
        ];
    }
}
