<?php

namespace Database\Factories;

use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

class PositionFactory extends Factory
{
    protected $model = Position::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->jobTitle(),
            'priority' => $this->faker->numberBetween(1, 100),
        ];
    }

    public function preset(): static
    {
        return $this->sequence(
            ['name' => 'Директор', 'priority' => 100],
            ['name' => 'Менеджер', 'priority' => 50],
            ['name' => 'Специалист', 'priority' => 30],
            ['name' => 'Стажер', 'priority' => 10],
        );
    }
}
