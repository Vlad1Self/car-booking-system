<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;

class CarSearchData extends Data
{
    public string $start_time;
    public string $end_time;
    public ?int $car_model_id;
    public ?int $comfort_category_id;
    public ?int $driver_id;
}
