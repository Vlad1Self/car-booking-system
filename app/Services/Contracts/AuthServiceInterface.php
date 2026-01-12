<?php

namespace App\Services\Contracts;

use App\DTOs\LoginData;
use App\DTOs\RegisterData;
use App\DTOs\LogoutData;

interface AuthServiceInterface
{
    public function register(RegisterData $data): array;

    public function login(LoginData $data): ?array;

    public function logout(LogoutData $data): bool;
}
