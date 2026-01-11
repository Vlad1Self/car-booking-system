<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\CarModel;
use App\Models\ComfortCategory;
use App\Models\Driver;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CarDataSeeder extends Seeder
{
    public function run(): void
    {
        // Создаем категории комфорта
        $categories = [
            ['name' => 'Премиум', 'level' => 1, 'description' => 'Автомобили высшего класса'],
            ['name' => 'Бизнес', 'level' => 2, 'description' => 'Бизнес-класс'],
            ['name' => 'Комфорт', 'level' => 3, 'description' => 'Комфортабельные автомобили'],
            ['name' => 'Эконом', 'level' => 4, 'description' => 'Эконом-класс'],
        ];

        foreach ($categories as $category) {
            ComfortCategory::create($category);
        }

        // Создаем должности
        $positions = [
            ['name' => 'Директор', 'priority' => 100],
            ['name' => 'Менеджер', 'priority' => 50],
            ['name' => 'Специалист', 'priority' => 30],
            ['name' => 'Стажер', 'priority' => 10],
        ];

        foreach ($positions as $position) {
            Position::create($position);
        }

        // Привязываем категории к должностям
        $premiumCategory = ComfortCategory::where('level', 1)->first();
        $businessCategory = ComfortCategory::where('level', 2)->first();
        $comfortCategory = ComfortCategory::where('level', 3)->first();
        $economyCategory = ComfortCategory::where('level', 4)->first();

        $director = Position::where('name', 'Директор')->first();
        $director->comfortCategories()->attach([$premiumCategory->id, $businessCategory->id]);

        $manager = Position::where('name', 'Менеджер')->first();
        $manager->comfortCategories()->attach([$businessCategory->id, $comfortCategory->id]);

        $specialist = Position::where('name', 'Специалист')->first();
        $specialist->comfortCategories()->attach([$comfortCategory->id]);

        $intern = Position::where('name', 'Стажер')->first();
        $intern->comfortCategories()->attach([$economyCategory->id]);

        // Создаем тестового пользователя
        $user = User::create([
            'name' => 'Тестовый Менеджер',
            'email' => 'manager@test.com',
            'password' => Hash::make('password'),
            'position_id' => $manager->id,
        ]);

        // Создаем водителей
        $drivers = [
            ['name' => 'Иван Иванов', 'phone' => '+79991234567'],
            ['name' => 'Петр Петров', 'phone' => '+79997654321'],
            ['name' => 'Сергей Сергеев', 'phone' => '+79999876543'],
        ];

        foreach ($drivers as $driver) {
            Driver::create($driver);
        }

        // Создаем модели автомобилей
        $carModels = [
            ['name' => 'S-Class', 'brand' => 'Mercedes', 'comfort_category_id' => $premiumCategory->id],
            ['name' => '7 Series', 'brand' => 'BMW', 'comfort_category_id' => $premiumCategory->id],
            ['name' => 'Camry', 'brand' => 'Toyota', 'comfort_category_id' => $businessCategory->id],
            ['name' => 'Accord', 'brand' => 'Honda', 'comfort_category_id' => $comfortCategory->id],
            ['name' => 'Solaris', 'brand' => 'Hyundai', 'comfort_category_id' => $economyCategory->id],
        ];

        foreach ($carModels as $model) {
            CarModel::create($model);
        }

        // Создаем автомобили
        $cars = [
            [
                'license_plate' => 'A001AA',
                'color' => 'Black',
                'year' => 2023,
                'car_model_id' => CarModel::where('name', 'S-Class')->first()->id,
                'driver_id' => Driver::where('name', 'Иван Иванов')->first()->id,
                'features' => ['climate_control', 'navigation', 'leather_seats'],
            ],
            [
                'license_plate' => 'B002BB',
                'color' => 'White',
                'year' => 2022,
                'car_model_id' => CarModel::where('name', 'Camry')->first()->id,
                'driver_id' => Driver::where('name', 'Петр Петров')->first()->id,
                'features' => ['climate_control', 'parking_sensors'],
            ],
            [
                'license_plate' => 'C003CC',
                'color' => 'Silver',
                'year' => 2021,
                'car_model_id' => CarModel::where('name', 'Accord')->first()->id,
                'driver_id' => Driver::where('name', 'Сергей Сергеев')->first()->id,
                'features' => ['air_conditioning'],
            ],
        ];

        foreach ($cars as $car) {
            Car::create($car);
        }

        // Создаем тестовую поездку
        \App\Models\Trip::create([
            'user_id' => $user->id,
            'car_id' => Car::where('license_plate', 'A001AA')->first()->id,
            'purpose' => 'Встреча с клиентом',
            'destination' => 'Бизнес-центр "Сокол"',
            'start_time' => now()->addDays(1)->setTime(10, 0),
            'end_time' => now()->addDays(1)->setTime(14, 0),
            'status' => 'planned',
        ]);
    }
}
