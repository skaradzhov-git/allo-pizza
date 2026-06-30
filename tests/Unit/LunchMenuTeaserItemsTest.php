<?php

namespace Tests\Unit;

use App\Models\LunchMenu;
use App\Models\LunchMenuItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LunchMenuTeaserItemsTest extends TestCase
{
    use RefreshDatabase;

    public function test_teaser_items_returns_one_item_from_each_main_section_group(): void
    {
        $soup = LunchMenuItem::query()->create([
            'section' => 'Супи',
            'name' => 'Таратор',
            'price' => 3.13,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $pizza = LunchMenuItem::query()->create([
            'section' => 'Пици',
            'name' => 'Маргарита 23 см',
            'price' => 12.90,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $dessert = LunchMenuItem::query()->create([
            'section' => 'Десерти',
            'name' => 'Мляко с ориз',
            'price' => 2.89,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        LunchMenuItem::query()->create([
            'section' => 'Десерти',
            'name' => 'Домашен чийзкейк',
            'price' => 5.90,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $menu = LunchMenu::query()->create([
            'title' => 'Обедно меню',
            'start_time' => '12:00:00',
            'end_time' => '16:00:00',
            'days_of_week' => [1, 2, 3, 4, 5],
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $menu->items()->sync([
            $dessert->id => ['sort_order' => 1],
            $soup->id => ['sort_order' => 2],
            $pizza->id => ['sort_order' => 3],
        ]);

        $menu->load('items');

        $teaserItems = $menu->teaserItems();

        $this->assertCount(3, $teaserItems);
        $this->assertSame('Таратор', $teaserItems[0]->name);
        $this->assertSame('Маргарита 23 см', $teaserItems[1]->name);
        $this->assertSame('Мляко с ориз', $teaserItems[2]->name);
    }

    public function test_teaser_items_prefers_salad_when_no_soup_is_available(): void
    {
        $salad = LunchMenuItem::query()->create([
            'section' => 'Салати',
            'name' => 'Шопска салата',
            'price' => 5.20,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $flatbread = LunchMenuItem::query()->create([
            'section' => 'Пърленки',
            'name' => 'Пърленка с масло',
            'price' => 3.90,
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

        $menu->items()->sync([
            $salad->id => ['sort_order' => 1],
            $flatbread->id => ['sort_order' => 2],
        ]);

        $menu->load('items');

        $teaserItems = $menu->teaserItems();

        $this->assertCount(2, $teaserItems);
        $this->assertSame('Шопска салата', $teaserItems[0]->name);
        $this->assertSame('Пърленка с масло', $teaserItems[1]->name);
    }

    public function test_items_grouped_by_meal_order_follows_restaurant_flow(): void
    {
        $menu = LunchMenu::query()->create([
            'title' => 'Обедно меню',
            'start_time' => '12:00:00',
            'end_time' => '16:00:00',
            'days_of_week' => [1, 2, 3, 4, 5],
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $createItem = fn (string $section, string $name, int $sortOrder) => LunchMenuItem::query()->create([
            'section' => $section,
            'name' => $name,
            'price' => 5,
            'is_active' => true,
            'sort_order' => $sortOrder,
        ]);

        $dessert = $createItem('Десерти', 'Мляко с ориз', 1);
        $soup = $createItem('Супи', 'Таратор', 1);
        $pizza = $createItem('Пици', 'Маргарита 23 см', 1);
        $drink = $createItem('Напитки', 'Айрян 500 мл', 1);

        $menu->items()->sync([
            $dessert->id => ['sort_order' => 1],
            $drink->id => ['sort_order' => 2],
            $pizza->id => ['sort_order' => 3],
            $soup->id => ['sort_order' => 4],
        ]);

        $menu->load('items');

        $this->assertSame(
            ['Супи', 'Пици', 'Напитки', 'Десерти'],
            $menu->itemsGroupedByMealOrder()->keys()->all(),
        );

        [$leftColumn, $rightColumn] = $menu->mealOrderColumns();

        $this->assertSame(['Супи', 'Пици'], $leftColumn->keys()->all());
        $this->assertSame(['Напитки', 'Десерти'], $rightColumn->keys()->all());
    }
}
