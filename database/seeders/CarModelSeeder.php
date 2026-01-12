<?php

namespace Database\Seeders;

use App\Models\CarModel;
use Illuminate\Database\Seeder;

class CarModelSeeder extends Seeder
{
    public function run(): void
    {
        if (CarModel::count() === 0) {
            CarModel::factory()->preset()->count(5)->create();
        }
    }
}
