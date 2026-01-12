<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CarSearchRequest;
use App\Http\Resources\Api\CarCollection;
use App\Services\Contracts\CarServiceInterface;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

class CarController extends Controller
{
    public function __construct(
        protected CarServiceInterface $carService
    ) {}

    public function getAvailableCars(CarSearchRequest $request): JsonResponse
    {
        $cars = $this->carService->getAvailableCars(
            $request->getDTO(),
            $request->user()
        );

        return ApiResponse::success(new CarCollection($cars));
    }
}
