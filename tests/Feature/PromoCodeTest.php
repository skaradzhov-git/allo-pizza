<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\PromoCode;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\Concerns\CreatesStoreData;
use Tests\TestCase;

class PromoCodeTest extends TestCase
{
    use RefreshDatabase;
    use CreatesStoreData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_promo_code_applies_discount_to_order(): void
    {
        Mail::fake();
        $this->createOpenStore();
        $product = $this->createProductWithVariant(20);
        $variant = $product->variants->first();

        PromoCode::query()->create([
            'code' => 'PIZZA20',
            'discount_percent' => 20,
            'is_active' => true,
            'used_count' => 0,
        ]);

        $customer = Customer::factory()->create();
        $this->actingAs($customer->user);

        $this->post('/cart/add', [
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $this->withSession(['promo_code' => 'PIZZA20'])->post('/checkout', [
            'customer_name' => 'Тест',
            'customer_phone' => '0888000000',
            'delivery_type' => 'pickup',
            'payment_method' => 'pay_at_store',
        ])->assertRedirect();

        $order = Order::query()->first();
        $price = (float) $variant->price;

        $this->assertEqualsWithDelta(round($price * 0.2, 2), (float) $order->discount, 0.01);
        $this->assertEqualsWithDelta($price - round($price * 0.2, 2), (float) $order->total, 0.01);
        $this->assertSame('PIZZA20', $order->promo_code);
        $this->assertSame(1, PromoCode::query()->first()->used_count);
    }

    public function test_valid_promo_code_endpoint_stores_code_in_session(): void
    {
        $this->createOpenStore();

        PromoCode::query()->create([
            'code' => 'PIZZA20',
            'discount_percent' => 20,
            'is_active' => true,
            'used_count' => 0,
        ]);

        $this->post('/cart/promo', ['code' => 'PIZZA20'])
            ->assertRedirect()
            ->assertSessionHas('promo_code', 'PIZZA20')
            ->assertSessionHas('status');
    }

    public function test_invalid_promo_code_is_rejected(): void
    {
        $this->createOpenStore();

        $this->post('/cart/promo', ['code' => 'NOPE'])
            ->assertSessionHas('error')
            ->assertSessionMissing('promo_code');
    }
}
