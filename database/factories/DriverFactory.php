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
            'is_active' => true,
        ];
    }

    public function preset(): static
    {
        return $this->sequence(
            ['name' => 'Иван Иванов', 'phone' => '+79991234567'],
            ['name' => 'Петр Петров', 'phone' => '+79997654321'],
            ['name' => 'Сергей Сергеев', 'phone' => '+79990001122'],
            ['name' => 'Алексей Алексеев', 'phone' => '+79995554433'],
            ['name' => 'Дмитрий Дмитриев', 'phone' => '+79998887766'],
        );
    }
}
