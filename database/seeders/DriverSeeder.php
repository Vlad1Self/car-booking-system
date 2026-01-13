<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    public function run(): void
    {
        if (Driver::count() === 0) {
            Driver::factory()->preset()->count(5)->create();
        }
    }
}
