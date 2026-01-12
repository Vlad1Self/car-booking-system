<?php

namespace Tests\Feature\Api;

use App\Models\Car;
use App\Models\CarModel;
use App\Models\ComfortCategory;
use App\Models\Driver;
use App\Models\Position;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarApiTest extends TestCase
{
    use RefreshDatabase;

    private User $manager;
    private ComfortCategory $businessCategory;
    private Car $availableCar;

    protected function setUp(): void
    {
        parent::setUp();

        $this->businessCategory = ComfortCategory::create(['name' => 'Бизнес', 'level' => 2]);
        $economyCategory = ComfortCategory::create(['name' => 'Эконом', 'level' => 4]);

        $position = Position::create(['name' => 'Менеджер']);
        $position->comfortCategories()->attach($this->businessCategory->id);

        $this->manager = User::create([
            'name' => 'Manager',
            'email' => 'manager@test.com',
            'password' => bcrypt('password'),
            'position_id' => $position->id,
        ]);

        $driver1 = Driver::create(['name' => 'Driver 1', 'phone' => '123']);
        $driver2 = Driver::create(['name' => 'Driver 2', 'phone' => '456']);

        $modelBus = CarModel::create(['name' => 'Camry', 'brand' => 'Toyota', 'comfort_category_id' => $this->businessCategory->id]);
        $modelEco = CarModel::create(['name' => 'Solaris', 'brand' => 'Hyundai', 'comfort_category_id' => $economyCategory->id]);

        $this->availableCar = Car::create([
            'license_plate' => 'BUS001',
            'car_model_id' => $modelBus->id,
            'driver_id' => $driver1->id,
            'year' => 2023,
            'color' => 'Black',
            'is_active' => true,
        ]);

        Car::create([
            'license_plate' => 'ECO001',
            'car_model_id' => $modelEco->id,
            'driver_id' => $driver2->id,
            'year' => 2022,
            'color' => 'White',
            'is_active' => true,
        ]);
    }

    public function test_manager_receives_only_available_comfort_categories(): void
    {
        $response = $this->actingAs($this->manager, 'sanctum')
            ->getJson('/api/available-cars?start_time=2027-01-01 10:00:00&end_time=2027-01-01 12:00:00');

        $response->assertStatus(200);
        $data = $response->json('data.items');

        $this->assertCount(1, $data);
        $this->assertEquals('BUS001', $data[0]['license_plate']);
    }

    public function test_car_is_not_available_if_already_booked(): void
    {
        Trip::create([
            'user_id' => $this->manager->id,
            'car_id' => $this->availableCar->id,
            'start_time' => '2027-01-01 10:00:00',
            'end_time' => '2027-01-01 12:00:00',
            'purpose' => 'Test',
            'destination' => 'Test',
            'status' => 'planned',
        ]);

        $response = $this->actingAs($this->manager, 'sanctum')
            ->getJson('/api/available-cars?start_time=2027-01-01 10:30:00&end_time=2027-01-01 11:30:00');

        $response->assertStatus(200);
        $this->assertCount(0, $response->json('data.items'));
    }

    public function test_filtering_by_car_model(): void
    {
        $response = $this->actingAs($this->manager, 'sanctum')
            ->getJson('/api/available-cars?start_time=2027-01-01 10:00:00&end_time=2027-01-01 12:00:00&car_model_id=' . $this->availableCar->car_model_id);

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data.items'));

        $responseWrongModel = $this->actingAs($this->manager, 'sanctum')
            ->getJson('/api/available-cars?start_time=2027-01-01 10:00:00&end_time=2027-01-01 12:00:00&car_model_id=999');

        $responseWrongModel->assertStatus(422);
    }

    public function test_unauthenticated_user_cannot_access_cars(): void
    {
        $response = $this->getJson('/api/available-cars?start_time=2027-01-01 10:00:00&end_time=2027-01-01 12:00:00');
        $response->assertStatus(401);
    }
}
