<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ComfortCategorySeeder::class,
            PositionSeeder::class,
            UserSeeder::class,
            DriverSeeder::class,
            CarModelSeeder::class,
            CarSeeder::class,
            TripSeeder::class,
        ]);
    }
}
