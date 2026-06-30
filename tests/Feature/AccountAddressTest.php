<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Customer;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountAddressTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_customer_can_store_address(): void
    {
        $customer = Customer::factory()->create();
        $this->actingAs($customer->user);

        $this->post('/account/addresses', [
            'label' => 'Вкъщи',
            'address_line' => 'ул. Тест 1',
            'city' => 'Русе',
        ])->assertRedirect();

        $this->assertDatabaseHas('addresses', [
            'customer_id' => $customer->id,
            'address_line' => 'ул. Тест 1',
            'is_default' => true,
        ]);
    }

    public function test_customer_can_delete_own_address(): void
    {
        $customer = Customer::factory()->create();
        $address = Address::query()->create([
            'customer_id' => $customer->id,
            'address_line' => 'ул. Изтрий 2',
            'is_default' => true,
        ]);

        $this->actingAs($customer->user)
            ->delete("/account/addresses/{$address->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('addresses', ['id' => $address->id]);
    }

    public function test_customer_can_update_own_address(): void
    {
        $customer = Customer::factory()->create();
        $address = Address::query()->create([
            'customer_id' => $customer->id,
            'label' => 'Вкъщи',
            'address_line' => 'ул. Стара 1',
            'city' => 'Русе',
            'is_default' => true,
        ]);

        $this->actingAs($customer->user)
            ->patch("/account/addresses/{$address->id}", [
                'label' => 'Офис',
                'address_line' => 'ул. Нова 5',
                'city' => 'Русе',
                'postal_code' => '7000',
                'is_default' => true,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'label' => 'Офис',
            'address_line' => 'ул. Нова 5',
            'postal_code' => '7000',
        ]);
    }

    public function test_customer_cannot_update_others_address(): void
    {
        $owner = Customer::factory()->create();
        $other = Customer::factory()->create();
        $address = Address::query()->create([
            'customer_id' => $owner->id,
            'address_line' => 'ул. Чужда 3',
        ]);

        $this->actingAs($other->user)
            ->patch("/account/addresses/{$address->id}", [
                'address_line' => 'ул. Хак 9',
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'address_line' => 'ул. Чужда 3',
        ]);
    }

    public function test_customer_cannot_delete_others_address(): void
    {
        $owner = Customer::factory()->create();
        $other = Customer::factory()->create();
        $address = Address::query()->create([
            'customer_id' => $owner->id,
            'address_line' => 'ул. Чужда 3',
        ]);

        $this->actingAs($other->user)
            ->delete("/account/addresses/{$address->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('addresses', ['id' => $address->id]);
    }
}
