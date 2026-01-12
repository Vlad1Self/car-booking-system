<?php

namespace Database\Factories;

use App\Models\Trip;
use App\Models\User;
use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

class TripFactory extends Factory
{
    protected $model = Trip::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'car_id' => Car::factory(),
            'purpose' => $this->faker->sentence(),
            'destination' => $this->faker->address(),
            'start_time' => now()->addDay(),
            'end_time' => now()->addDay()->addHours(2),
            'status' => 'planned',
        ];
    }

    public function preset(): static
    {
        return $this->state(fn(array $attributes) => [
            'user_id' => User::where('email', 'manager@test.com')->first()?->id ?? User::factory(),
            'car_id' => Car::where('license_plate', 'A001AA')->first()?->id ?? Car::factory(),
            'purpose' => 'Встреча с клиентом',
            'destination' => 'Бизнес-центр "Сокол"',
            'start_time' => now()->addDays(1)->setTime(10, 0),
            'end_time' => now()->addDays(1)->setTime(14, 0),
            'status' => 'planned',
        ]);
    }
}
