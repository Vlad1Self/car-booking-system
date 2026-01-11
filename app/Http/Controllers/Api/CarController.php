<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{
    public function getAvailableCars(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'car_model' => 'nullable|string|max:100',
            'comfort_category' => 'nullable|string|max:50',
            'driver_name' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');

        if (!$user->position || $user->availableComfortCategories->isEmpty()) {
            return response()->json([
                'message' => 'У вас нет доступа к служебным автомобилям',
                'data' => []
            ], 200);
        }

        $query = Car::with(['carModel.comfortCategory', 'driver'])
            ->where('is_active', true)
            ->availableForUser($user)
            ->availableBetween($startTime, $endTime);

        if ($request->has('car_model')) {
            $searchTerm = $request->input('car_model');
            $query->whereHas('carModel', function ($q) use ($searchTerm) {
                $q->where(function ($sub) use ($searchTerm) {
                    $sub->where('car_models.name', 'like', "%{$searchTerm}%")
                        ->orWhere('car_models.brand', 'like', "%{$searchTerm}%");
                });
            });
        }

        if ($request->has('comfort_category')) {
            $searchTerm = $request->input('comfort_category');
            $query->whereHas('carModel.comfortCategory', function ($q) use ($searchTerm) {
                // Группируем OR условия и указываем таблицу
                $q->where(function ($sub) use ($searchTerm) {
                    $sub->where('comfort_categories.name', 'like', "%{$searchTerm}%")
                        ->orWhere('comfort_categories.level', 'like', "%{$searchTerm}%");
                });
            });
        }

        if ($request->has('driver_name')) {
            $searchTerm = $request->input('driver_name');
            $query->whereHas('driver', function ($q) use ($searchTerm) {
                $q->where('drivers.name', 'like', "%{$searchTerm}%");
            });
        }

        $query->join('car_models', 'cars.car_model_id', '=', 'car_models.id')
            ->join('comfort_categories', 'car_models.comfort_category_id', '=', 'comfort_categories.id')
            ->orderBy('comfort_categories.level', 'desc')
            ->select('cars.*');

        $cars = $query->get()->map(function ($car) {
            return [
                'id' => $car->id,
                'license_plate' => $car->license_plate,
                'full_name' => $car->full_name,
                'color' => $car->color,
                'year' => $car->year,
                'features' => $car->features,
                'driver' => [
                    'id' => $car->driver->id,
                    'name' => $car->driver->name,
                    'phone' => $car->driver->phone,
                ],
                'car_model' => [
                    'id' => $car->carModel->id,
                    'name' => $car->carModel->name,
                    'brand' => $car->carModel->brand,
                    'comfort_category' => [
                        'id' => $car->carModel->comfortCategory->id,
                        'name' => $car->carModel->comfortCategory->name,
                        'level' => $car->carModel->comfortCategory->level,
                    ]
                ]
            ];
        });

        return response()->json([
            'data' => $cars,
            'meta' => [
                'total' => $cars->count(),
                'start_time' => $startTime,
                'end_time' => $endTime,
                'user_position' => $user->position->name,
                'available_categories' => $user->availableComfortCategories->pluck('name')
            ]
        ]);
    }
}
