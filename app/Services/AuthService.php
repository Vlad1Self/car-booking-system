<?php

namespace App\Services;

use App\Models\User;
use App\DTOs\LoginData;
use App\DTOs\RegisterData;
use App\DTOs\LogoutData;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService implements AuthServiceInterface
{
    public function register(RegisterData $data): array
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data->name,
                'email' => $data->email,
                'password' => Hash::make($data->password),
                'position_id' => $data->position_id,
            ]);

            $token = $user
                ->createToken($data->device_name ?? 'web_app')
                ->plainTextToken;

            return [
                'token' => $token,
                'user' => $user,
            ];
        });
    }

    public function login(LoginData $data): ?array
    {
        $user = User::where('email', $data->email)->first();

        if (!$user || !Hash::check($data->password, $user->password)) {
            return null;
        }

        $token = $user->createToken($data->device_name)->plainTextToken;

        return [
            'token' => $token,
            'user' => $user,
        ];
    }

    public function logout(LogoutData $data): bool
    {
        if ($data->all_devices) {
            return (bool) $data->user->tokens()->delete();
        }

        /** @var PersonalAccessToken|null $token */
        $token = $data->user->currentAccessToken();

        return (bool) $token?->delete();
    }
}
