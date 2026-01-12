<?php

namespace App\Services;

use App\DTOs\CarSearchData;
use App\Models\Car;
use App\Models\User;
use App\Services\Contracts\CarServiceInterface;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CarService implements CarServiceInterface
{
    public function getAvailableCars(CarSearchData $data, User $user): Collection
    {
        if ($user->available_comfort_categories->isEmpty()) {
            throw new AccessDeniedHttpException('Вашей должности не назначены категории комфорта');
        }

        return Car::query()
            ->with(['carModel.comfortCategory', 'driver'])
            ->active()
            ->forUser($user)
            ->availableBetween($data->start_time, $data->end_time)
            ->when($data->car_model_id, fn($q, $val) => $q->where('car_model_id', $val))
            ->when($data->comfort_category_id, fn($q, $val) => $q->whereRelation('carModel', 'comfort_category_id', $val))
            ->when($data->driver_id, fn($q, $val) => $q->where('driver_id', $val))
            ->latest('year')
            ->get();
    }
}
