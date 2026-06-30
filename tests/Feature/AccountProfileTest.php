<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Tests\TestCase;

class AccountProfileTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_user_without_customer_record_can_update_profile_phone(): void
    {
        $user = User::factory()->create([
            'name' => 'Администратор',
            'email' => 'admin@example.test',
        ]);

        $this->actingAs($user)
            ->patch('/account/profile', [
                'name' => 'Администратор',
                'email' => 'admin@example.test',
                'phone' => '0899111222',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('customers', [
            'user_id' => $user->id,
            'name' => 'Администратор',
            'email' => 'admin@example.test',
            'phone' => '0899111222',
        ]);
    }
}
