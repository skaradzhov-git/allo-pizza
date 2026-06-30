<?php

namespace Tests\Feature;

use App\Enums\CartItemType;
use App\Models\Customer;
use App\Models\LunchMenu;
use App\Models\LunchMenuItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Carbon;
use Tests\Concerns\CreatesStoreData;
use Tests\TestCase;

class LunchMenuCheckoutTest extends TestCase
{
    use CreatesStoreData;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_checkout_with_lunch_items_creates_order_snapshot(): void
    {
        Carbon::setTestNow(Carbon::parse('next monday 13:00'));
        $this->createOpenStore();

        $customer = Customer::factory()->create();
        $this->actingAs($customer->user);

        $item = LunchMenuItem::query()->create([
            'section' => 'Супи',
            'name' => 'Таратор',
            'description' => '300 гр.',
            'price' => 3.13,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $menu = LunchMenu::query()->create([
            'title' => 'Обедно меню',
            'start_time' => '12:00:00',
            'end_time' => '16:00:00',
            'days_of_week' => [1, 2, 3, 4, 5],
            'is_active' => true,
            'sort_order' => 1,
        ]);
        $menu->items()->sync([$item->id]);

        $this->post(route('lunch.items.add', $item), ['quantity' => 2])
            ->assertRedirect(route('cart'));

        $this->assertDatabaseHas('cart_items', [
            'item_type' => CartItemType::LunchItem->value,
            'lunch_menu_item_id' => $item->id,
            'quantity' => 2,
        ]);

        $response = $this->post(route('checkout.store'), [
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'customer_phone' => $customer->phone,
            'delivery_type' => 'pickup',
            'payment_method' => 'pay_at_store',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('order_items', [
            'item_type' => CartItemType::LunchItem->value,
            'product_name' => 'Таратор',
            'variant_name' => 'Обедно меню',
            'quantity' => 2,
        ]);
    }
}
