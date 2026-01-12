<?php

namespace Database\Seeders;

use App\Models\Trip;
use Illuminate\Database\Seeder;

class TripSeeder extends Seeder
{
    public function run(): void
    {
        if (Trip::count() === 0) {
            Trip::factory()->preset()->create();
        }
    }
}
