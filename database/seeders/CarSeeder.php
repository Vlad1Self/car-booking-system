<?php

namespace Database\Seeders;

use App\Models\Car;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        if (Car::count() === 0) {
            Car::factory()->preset()->count(3)->create();
        }
    }
}
