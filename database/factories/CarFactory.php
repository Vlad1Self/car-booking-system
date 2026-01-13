<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\CarModel;
use App\Models\Driver;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarFactory extends Factory
{
    protected $model = Car::class;

    public function definition(): array
    {
        return [
            'license_plate' => strtoupper($this->faker->bothify('?###??')),
            'color' => $this->faker->safeColorName(),
            'year' => $this->faker->year(),
            'car_model_id' => CarModel::factory(),
            'driver_id' => Driver::factory(),
            'is_active' => true,
            'features' => [],
        ];
    }

    public function preset(): static
    {
        return $this->sequence(
            fn($sequence) => [
                'license_plate' => 'A001AA',
                'color' => 'Black',
                'year' => 2023,
                'car_model_id' => $this->findModel('S-Class'),
                'driver_id' => Driver::where('name', 'Иван Иванов')->first()?->id ?? Driver::factory(),
                'features' => ['climate_control', 'navigation', 'leather_seats'],
            ],
            fn($sequence) => [
                'license_plate' => 'B002BB',
                'color' => 'White',
                'year' => 2022,
                'car_model_id' => $this->findModel('Camry'),
                'driver_id' => Driver::where('name', 'Петр Петров')->first()?->id ?? Driver::factory(),
                'features' => ['climate_control', 'parking_sensors'],
            ],
            fn($sequence) => [
                'license_plate' => 'C003CC',
                'color' => 'Silver',
                'year' => 2021,
                'car_model_id' => $this->findModel('7 Series'),
                'driver_id' => Driver::where('name', 'Сергей Сергеев')->first()?->id ?? Driver::factory(),
                'features' => ['climate_control', 'massage_seats', 'soft_close_doors'],
            ],
            fn($sequence) => [
                'license_plate' => 'D004DD',
                'color' => 'Blue',
                'year' => 2020,
                'car_model_id' => $this->findModel('Accord'),
                'driver_id' => Driver::where('name', 'Алексей Алексеев')->first()?->id ?? Driver::factory(),
                'features' => ['air_conditioning', 'cruise_control'],
            ],
            fn($sequence) => [
                'license_plate' => 'E005EE',
                'color' => 'Gray',
                'year' => 2019,
                'car_model_id' => $this->findModel('Solaris'),
                'driver_id' => Driver::where('name', 'Дмитрий Дмитриев')->first()?->id ?? Driver::factory(),
                'features' => ['air_conditioning'],
            ],
        );
    }

    private function findModel(string $name): int
    {
        return CarModel::where('name', $name)->first()?->id ?? CarModel::factory()->create(['name' => $name])->id;
    }
}
