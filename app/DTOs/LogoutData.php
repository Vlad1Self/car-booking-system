<?php

namespace App\DTOs;

use App\Models\User;
use Spatie\LaravelData\Data;

class LogoutData extends Data
{
    public User $user;
    public bool $all_devices = false;
}
