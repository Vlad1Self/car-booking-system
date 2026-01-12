<?php

namespace Database\Factories;

use App\Models\CarModel;
use App\Models\ComfortCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarModelFactory extends Factory
{
    protected $model = CarModel::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'brand' => $this->faker->company(),
            'comfort_category_id' => ComfortCategory::factory(),
        ];
    }

    public function preset(): static
    {
        return $this->sequence(
            fn($sequence) => [
                'name' => 'S-Class',
                'brand' => 'Mercedes',
                'comfort_category_id' => ComfortCategory::where('level', 1)->first()?->id ?? ComfortCategory::factory()
            ],
            fn($sequence) => [
                'name' => '7 Series',
                'brand' => 'BMW',
                'comfort_category_id' => ComfortCategory::where('level', 1)->first()?->id ?? ComfortCategory::factory()
            ],
            fn($sequence) => [
                'name' => 'Camry',
                'brand' => 'Toyota',
                'comfort_category_id' => ComfortCategory::where('level', 2)->first()?->id ?? ComfortCategory::factory()
            ],
            fn($sequence) => [
                'name' => 'Accord',
                'brand' => 'Honda',
                'comfort_category_id' => ComfortCategory::where('level', 3)->first()?->id ?? ComfortCategory::factory()
            ],
            fn($sequence) => [
                'name' => 'Solaris',
                'brand' => 'Hyundai',
                'comfort_category_id' => ComfortCategory::where('level', 4)->first()?->id ?? ComfortCategory::factory()
            ],
        );
    }
}
