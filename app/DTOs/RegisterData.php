<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;

class RegisterData extends Data
{
    public string $name;
    public string $email;
    public string $password;
    public ?int $position_id;
    public ?string $device_name;
}
