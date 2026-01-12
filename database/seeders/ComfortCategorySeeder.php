<?php

namespace Database\Seeders;

use App\Models\ComfortCategory;
use Illuminate\Database\Seeder;

class ComfortCategorySeeder extends Seeder
{
    public function run(): void
    {
        if (ComfortCategory::count() === 0) {
            ComfortCategory::factory()->preset()->count(4)->create();
        }
    }
}
