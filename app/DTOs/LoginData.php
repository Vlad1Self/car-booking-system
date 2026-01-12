<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;

class LoginData extends Data
{
    public string $email;
    public string $password;
    public ?string $device_name;
}
