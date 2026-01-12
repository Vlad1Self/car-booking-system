<?php

namespace Database\Factories;

use App\Models\Driver;
use Illuminate\Database\Eloquent\Factories\Factory;

class DriverFactory extends Factory
{
    protected $model = Driver::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
        ];
    }

    public function preset(): static
    {
        return $this->sequence(
            ['name' => 'Иван Иванов', 'phone' => '+79991234567'],
            ['name' => 'Петр Петров', 'phone' => '+79997654321'],
            ['name' => 'Сергей Сергеев', 'phone' => '+79999876543'],
        );
    }
}
