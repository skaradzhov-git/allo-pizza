<?php

namespace Database\Seeders;

use App\Models\PromoCode;
use App\Support\Money;
use Illuminate\Database\Seeder;

class PromoCodeSeeder extends Seeder
{
    public function run(): void
    {
        PromoCode::query()->updateOrCreate(
            ['code' => 'ALLO10'],
            [
                'description' => '10% отстъпка за нови клиенти',
                'discount_percent' => 10,
                'minimum_order_amount' => Money::fromBgn(20),
                'is_active' => true,
                'used_count' => 0,
            ],
        );

        PromoCode::query()->updateOrCreate(
            ['code' => 'PIZZA5'],
            [
                'description' => '2.56 € отстъпка над 15.33 €',
                'discount_amount' => Money::fromBgn(5),
                'minimum_order_amount' => Money::fromBgn(30),
                'is_active' => true,
                'used_count' => 0,
            ],
        );
    }
}
