<?php

namespace Database\Seeders;

use App\Models\Position;
use App\Models\ComfortCategory;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        if (Position::count() === 0) {
            Position::factory()->preset()->count(4)->create();
        }

        $this->attachCategories();
    }

    private function attachCategories(): void
    {
        $premium = ComfortCategory::where('level', 1)->first();
        $business = ComfortCategory::where('level', 2)->first();
        $comfort = ComfortCategory::where('level', 3)->first();
        $economy = ComfortCategory::where('level', 4)->first();

        if ($premium && $business && $comfort && $economy) {
            Position::where('name', 'Директор')->first()?->comfortCategories()->syncWithoutDetaching([$premium->id, $business->id]);
            Position::where('name', 'Менеджер')->first()?->comfortCategories()->syncWithoutDetaching([$business->id, $comfort->id]);
            Position::where('name', 'Специалист')->first()?->comfortCategories()->syncWithoutDetaching([$comfort->id]);
            Position::where('name', 'Стажер')->first()?->comfortCategories()->syncWithoutDetaching([$economy->id]);
        }
    }
}
