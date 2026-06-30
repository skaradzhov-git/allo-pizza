<?php

namespace Database\Seeders;

use App\Models\LunchMenuItem;
use App\Support\Money;
use Illuminate\Database\Seeder;

class LunchMenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['section' => 'Супи', 'name' => 'Таратор', 'description' => '300 гр. – кисело мляко, краставици, орехи, копър', 'price' => 3.13, 'sort_order' => 1],
            ['section' => 'Супи', 'name' => 'Пилешка супа', 'description' => '300 гр. – пилешко месо, зеленчуци, фиде', 'price' => 4.30, 'sort_order' => 2],
            ['section' => 'Супи', 'name' => 'Крем супа от червена леща', 'description' => '300 гр. – леща, моркови, подправки', 'price' => 3.21, 'sort_order' => 3],
            ['section' => 'Салати', 'name' => 'Шопска салата', 'description' => '250 гр. – домати, краставици, чушки, лук, сирене', 'price' => 5.20, 'sort_order' => 1, 'is_hit' => true],
            ['section' => 'Салати', 'name' => 'Салата Столична', 'description' => '200 гр. – картофи, моркови, кисели краставички', 'price' => 3.50, 'sort_order' => 2],
            ['section' => 'Салати', 'name' => 'Цезар с пиле', 'description' => '230 гр. – айсберг, пилешко филе, крутони, пармезан', 'price' => 6.90, 'sort_order' => 3, 'is_new' => true],
            ['section' => 'Салати', 'name' => 'Гръцка салата', 'description' => '230 гр. – домати, краставици, маслини, сирене', 'price' => 5.28, 'sort_order' => 4],
            ['section' => 'Пици', 'name' => 'Маргарита 23 см', 'description' => 'Моцарела, доматен сос, босилек', 'price' => 12.90, 'sort_order' => 1, 'is_hit' => true],
            ['section' => 'Пици', 'name' => 'Пеперони 23 см', 'description' => 'Пикантни салами пеперони, моцарела', 'price' => 14.90, 'sort_order' => 2, 'is_spicy' => true],
            ['section' => 'Пици', 'name' => 'Капричоза 23 см', 'description' => 'Шунка, гъби, маслини, моцарела', 'price' => 15.90, 'sort_order' => 3],
            ['section' => 'Пици', 'name' => 'Вегетариана 23 см', 'description' => 'Чушки, гъби, лук, маслини', 'price' => 13.90, 'sort_order' => 4, 'is_new' => true],
            ['section' => 'Пърленки', 'name' => 'Пърленка с масло', 'description' => 'Топла пърленка с масло и шарена сол', 'price' => 3.90, 'sort_order' => 1],
            ['section' => 'Пърленки', 'name' => 'Пърленка с кашкавал', 'description' => 'Пухкава пърленка с разтопен кашкавал', 'price' => 4.90, 'sort_order' => 2],
            ['section' => 'Пърленки', 'name' => 'Чеснова пърленка', 'description' => 'Пърленка с чесново масло и подправки', 'price' => 4.50, 'sort_order' => 3],
            ['section' => 'Пърленки', 'name' => 'Пърленка комбинирана', 'description' => 'Кашкавал, сирене и чесново масло', 'price' => 5.90, 'sort_order' => 4, 'is_hit' => true],
            ['section' => 'Напитки', 'name' => 'Кока-Кола 500 мл', 'description' => 'Студена газирана напитка', 'price' => 2.90, 'sort_order' => 1],
            ['section' => 'Напитки', 'name' => 'Минерална вода 500 мл', 'description' => 'Освежаваща минерална вода', 'price' => 1.90, 'sort_order' => 2],
            ['section' => 'Напитки', 'name' => 'Айрян 500 мл', 'description' => 'Студен айрян', 'price' => 2.40, 'sort_order' => 3],
            ['section' => 'Напитки', 'name' => 'Домашна лимонада', 'description' => 'Лимон, мента и свеж вкус', 'price' => 3.90, 'sort_order' => 4, 'is_new' => true],
            ['section' => 'Десерти', 'name' => 'Мляко с ориз', 'description' => '200 гр. – домашен десерт с канела', 'price' => 2.89, 'sort_order' => 1],
            ['section' => 'Десерти', 'name' => 'Домашен чийзкейк', 'description' => 'Кремообразен десерт с бисквитена основа', 'price' => 5.90, 'sort_order' => 2, 'is_new' => true],
            ['section' => 'Десерти', 'name' => 'Палачинка с шоколад', 'description' => 'Топла палачинка с шоколадов крем', 'price' => 4.90, 'sort_order' => 3],
        ];

        foreach ($items as $item) {
            LunchMenuItem::query()->updateOrCreate(
                ['section' => $item['section'], 'name' => $item['name']],
                array_merge($item, [
                    'price' => Money::fromBgn((float) $item['price']),
                    'is_active' => true,
                    'is_spicy' => $item['is_spicy'] ?? false,
                    'is_hit' => $item['is_hit'] ?? false,
                    'is_new' => $item['is_new'] ?? false,
                ])
            );
        }
    }
}
