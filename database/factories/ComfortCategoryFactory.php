<?php

namespace Database\Factories;

use App\Models\ComfortCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComfortCategoryFactory extends Factory
{
    protected $model = ComfortCategory::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'level' => $this->faker->unique()->numberBetween(1, 10),
            'description' => $this->faker->sentence(),
        ];
    }

    public function preset(): static
    {
        return $this->sequence(
            ['name' => 'Премиум', 'level' => 1, 'description' => 'Автомобили высшего класса'],
            ['name' => 'Бизнес', 'level' => 2, 'description' => 'Бизнес-класс'],
            ['name' => 'Комфорт', 'level' => 3, 'description' => 'Комфортабельные автомобили'],
            ['name' => 'Эконом', 'level' => 4, 'description' => 'Эконом-класс'],
        );
    }
}
