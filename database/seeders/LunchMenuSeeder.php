<?php

namespace Database\Seeders;

use App\Models\LunchMenu;
use App\Models\LunchMenuItem;
use Illuminate\Database\Seeder;

class LunchMenuSeeder extends Seeder
{
    public function run(): void
    {
        $lunchMenu = LunchMenu::query()->updateOrCreate(
            ['title' => 'Обедно меню'],
            [
                'description' => 'Комбинирайте пица, пърленка, салата, напитка или десерт на специална обедна цена.',
                'message' => 'Обедните предложения важат всеки делничен ден между 12:00 и 16:00 ч.',
                'start_time' => '12:00:00',
                'end_time' => '16:00:00',
                'days_of_week' => [1, 2, 3, 4, 5],
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $itemIds = LunchMenuItem::query()
            ->where('is_active', true)
            ->get()
            ->sortBy(fn (LunchMenuItem $item) => $item->mealSortKey())
            ->pluck('id');

        $syncData = $itemIds->mapWithKeys(fn ($id, $index) => [$id => ['sort_order' => $index + 1]])->all();

        $lunchMenu->items()->sync($syncData);
    }
}
