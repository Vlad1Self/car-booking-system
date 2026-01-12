<?php

namespace Tests\Feature\Api;

use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private Position $position;

    protected function setUp(): void
    {
        parent::setUp();
        $this->position = Position::create(['name' => 'Менеджер', 'priority' => 50]);
    }

    public function test_user_can_register_with_valid_data(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'position_id' => $this->position->id,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('status.message', 'Регистрация успешна')
            ->assertJsonStructure([
                'data' => [
                    'token',
                    'user' => ['id', 'name', 'email', 'position']
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);
    }

    public function test_user_cannot_register_without_position(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('data.position_id', fn($val) => !empty($val));
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        User::create([
            'name' => 'Active User',
            'email' => 'active@example.com',
            'password' => Hash::make('secret123'),
            'position_id' => $this->position->id,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'active@example.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('status.message', 'Авторизация успешна')
            ->assertJsonStructure(['data' => ['token', 'user']]);
    }

    public function test_user_cannot_login_with_wrong_password(): void
    {
        User::create([
            'name' => 'Active User',
            'email' => 'active@example.com',
            'password' => Hash::make('secret123'),
            'position_id' => $this->position->id,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'active@example.com',
            'password' => 'wrong_password',
        ]);

        $response->assertStatus(401)
            ->assertJsonPath('status.message', 'Неверный email или пароль');
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::create([
            'name' => 'Logged User',
            'email' => 'logged@example.com',
            'password' => Hash::make('password'),
            'position_id' => $this->position->id,
        ]);

        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJsonPath('status.message', 'Успешный выход');

        $this->assertCount(0, $user->tokens);
    }
}
