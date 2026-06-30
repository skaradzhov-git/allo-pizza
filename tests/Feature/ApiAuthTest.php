<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_receive_token(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Иван',
            'email' => 'ivan@example.com',
            'phone' => '0888123456',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertCreated()
            ->assertJsonStructure(['token', 'user' => ['id', 'name', 'email', 'phone', 'role']]);

        $this->assertDatabaseHas('users', ['email' => 'ivan@example.com']);
        $this->assertDatabaseHas('customers', ['email' => 'ivan@example.com', 'phone' => '0888123456']);
    }

    public function test_user_can_login_and_access_me(): void
    {
        $customer = Customer::factory()->create();
        $customer->user->forceFill(['password' => bcrypt('secret123')])->save();

        $login = $this->postJson('/api/auth/login', [
            'email' => $customer->user->email,
            'password' => 'secret123',
        ]);

        $login->assertOk()->assertJsonStructure(['token', 'user']);
        $token = $login->json('token');

        $this->withToken($token)->getJson('/api/auth/me')
            ->assertOk()
            ->assertJsonPath('user.email', $customer->user->email);
    }

    public function test_login_with_wrong_password_fails(): void
    {
        $customer = Customer::factory()->create();
        $customer->user->forceFill(['password' => bcrypt('secret123')])->save();

        $this->postJson('/api/auth/login', [
            'email' => $customer->user->email,
            'password' => 'wrong',
        ])->assertStatus(422);
    }

    public function test_me_requires_authentication(): void
    {
        $this->getJson('/api/auth/me')->assertUnauthorized();
    }
}
