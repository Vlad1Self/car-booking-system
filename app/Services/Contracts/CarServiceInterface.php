<?php

namespace App\Services\Contracts;

use App\DTOs\CarSearchData;
use App\Models\User;
use Illuminate\Support\Collection;

interface CarServiceInterface
{
    public function getAvailableCars(CarSearchData $data, User $user): Collection;
}
