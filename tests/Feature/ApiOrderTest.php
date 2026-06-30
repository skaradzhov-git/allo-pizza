<?php

namespace Tests\Feature;

use App\Mail\NewOrderAdminNotification;
use App\Mail\OrderConfirmation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\Concerns\CreatesStoreData;
use Tests\TestCase;

class ApiOrderTest extends TestCase
{
    use RefreshDatabase;
    use CreatesStoreData;

    public function test_can_create_order_via_api(): void
    {
        Mail::fake();
        $this->createOpenStore();
        $product = $this->createProductWithVariant(15);
        $variant = $product->variants->first();

        $response = $this->postJson('/api/orders', [
            'customer_name' => 'Мария',
            'customer_email' => 'maria@example.com',
            'customer_phone' => '0899111222',
            'delivery_type' => 'pickup',
            'payment_method' => 'pay_at_store',
            'items' => [
                ['product_id' => $product->id, 'product_variant_id' => $variant->id, 'quantity' => 2],
            ],
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.customer_name', 'Мария');

        $this->assertEqualsWithDelta((float) $variant->price * 2, (float) $response->json('data.subtotal'), 0.01);
        $this->assertDatabaseHas('orders', ['customer_email' => 'maria@example.com']);
        Mail::assertSent(OrderConfirmation::class);
        Mail::assertSent(NewOrderAdminNotification::class);
    }

    public function test_api_order_requires_items(): void
    {
        $this->createOpenStore();

        $this->postJson('/api/orders', [
            'customer_name' => 'Мария',
            'customer_phone' => '0899111222',
            'delivery_type' => 'pickup',
            'payment_method' => 'pay_at_store',
            'items' => [],
        ])->assertStatus(422);
    }
}
