<?php

namespace App\Http\Controllers\Api;

use App\DTOs\LogoutData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\Api\UserResource;
use App\Http\Responses\ApiResponse;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        protected AuthServiceInterface $authService
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->getDTO());

        return ApiResponse::success([
            'token' => $result['token'],
            'user' => new UserResource($result['user']),
        ], message: 'Регистрация успешна');
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->getDTO());

        if (!$result) {
            return ApiResponse::error(401, 'Неверный email или пароль');
        }

        return ApiResponse::success([
            'token' => $result['token'],
            'user' => new UserResource($result['user']),
        ], message: 'Авторизация успешна');
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout(LogoutData::from([
            'user' => $request->user(),
            'all_devices' => $request->boolean('all_devices'),
        ]));

        return ApiResponse::success(message: 'Успешный выход');
    }
}
