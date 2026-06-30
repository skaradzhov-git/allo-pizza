<?php

namespace Tests\Feature;

use App\Enums\CartItemType;
use App\Models\LunchMenu;
use App\Models\LunchMenuItem;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class LunchMenuPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_lunch_page_shows_grouped_items(): void
    {
        $this->seedLunchCatalog();

        $this->get(route('lunch.index'))
            ->assertOk()
            ->assertSee('Обедно меню')
            ->assertSee('Таратор')
            ->assertSee('Маргарита 23 см')
            ->assertSee('Добави избраните');
    }

    public function test_can_add_single_lunch_item_to_cart(): void
    {
        $this->travelToActiveLunchWindow();

        $item = $this->seedLunchCatalog();

        $this->post(route('lunch.items.add', $item), ['quantity' => 2])
            ->assertRedirect(route('cart'));

        $this->assertDatabaseHas('cart_items', [
            'item_type' => CartItemType::LunchItem->value,
            'lunch_menu_item_id' => $item->id,
            'item_name' => $item->name,
            'quantity' => 2,
        ]);
    }

    public function test_can_add_multiple_selected_lunch_items(): void
    {
        $this->travelToActiveLunchWindow();

        $soup = LunchMenuItem::query()->create([
            'section' => 'Супи',
            'name' => 'Таратор',
            'description' => '300 гр.',
            'price' => 3.13,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $salad = LunchMenuItem::query()->create([
            'section' => 'Салати',
            'name' => 'Шопска салата',
            'description' => '250 гр.',
            'price' => 5.20,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $menu = LunchMenu::query()->create([
            'title' => 'Обедно меню',
            'description' => 'Тест',
            'message' => 'Тест',
            'start_time' => '12:00:00',
            'end_time' => '16:00:00',
            'days_of_week' => [1, 2, 3, 4, 5],
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $menu->items()->sync([$soup->id, $salad->id]);

        $this->post(route('lunch.add-selected'), [
            'selected' => [$soup->id, $salad->id],
            'quantities' => [
                $soup->id => 1,
                $salad->id => 2,
            ],
        ])->assertRedirect(route('cart'));

        $this->assertDatabaseHas('cart_items', [
            'item_name' => 'Таратор',
            'quantity' => 1,
        ]);

        $this->assertDatabaseHas('cart_items', [
            'item_name' => 'Шопска салата',
            'quantity' => 2,
        ]);
    }

    public function test_cannot_add_lunch_item_outside_active_window(): void
    {
        Carbon::setTestNow(Carbon::parse('next saturday 13:00'));

        $item = $this->seedLunchCatalog();

        $this->post(route('lunch.items.add', $item), ['quantity' => 1])
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseCount('cart_items', 0);
    }

    protected function seedLunchCatalog(): LunchMenuItem
    {
        Page::query()->create([
            'title' => 'Обедно меню',
            'slug' => 'obedno-menyu',
            'content' => '<p>Специални обедни предложения.</p>',
            'is_active' => true,
        ]);

        $item = LunchMenuItem::query()->create([
            'section' => 'Супи',
            'name' => 'Таратор',
            'description' => '300 гр.',
            'price' => 3.13,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        LunchMenuItem::query()->create([
            'section' => 'Пици',
            'name' => 'Маргарита 23 см',
            'description' => 'Моцарела и доматен сос',
            'price' => 12.90,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $menu = LunchMenu::query()->create([
            'title' => 'Обедно меню',
            'description' => 'Тест',
            'message' => 'Тест',
            'start_time' => '12:00:00',
            'end_time' => '16:00:00',
            'days_of_week' => [1, 2, 3, 4, 5],
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $menu->items()->sync(LunchMenuItem::query()->pluck('id'));

        return $item;
    }

    protected function travelToActiveLunchWindow(): void
    {
        Carbon::setTestNow(Carbon::parse('next monday 13:00'));
    }
}
