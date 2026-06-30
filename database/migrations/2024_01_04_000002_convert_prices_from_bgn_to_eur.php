<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $rate = (float) config('money.bgn_per_eur', 1.95583);

        $tables = [
            'products' => ['base_price', 'old_price'],
            'product_variants' => ['price'],
            'ingredients' => ['price'],
            'lunch_menu_items' => ['price'],
            'store_settings' => ['delivery_price', 'free_delivery_over', 'minimum_order_amount'],
            'promo_codes' => ['discount_amount', 'minimum_order_amount'],
            'cart_items' => ['unit_price', 'total_price'],
            'orders' => ['delivery_price', 'subtotal', 'discount', 'total'],
            'order_items' => ['unit_price', 'total_price'],
            'order_item_options' => ['price'],
        ];

        foreach ($tables as $table => $columns) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            foreach ($columns as $column) {
                if (! Schema::hasColumn($table, $column)) {
                    continue;
                }

                DB::table($table)
                    ->whereNotNull($column)
                    ->update([
                        $column => DB::raw("ROUND({$column} / {$rate}, 2)"),
                    ]);
            }
        }
    }

    public function down(): void
    {
        $rate = (float) config('money.bgn_per_eur', 1.95583);

        $tables = [
            'products' => ['base_price', 'old_price'],
            'product_variants' => ['price'],
            'ingredients' => ['price'],
            'lunch_menu_items' => ['price'],
            'store_settings' => ['delivery_price', 'free_delivery_over', 'minimum_order_amount'],
            'promo_codes' => ['discount_amount', 'minimum_order_amount'],
            'cart_items' => ['unit_price', 'total_price'],
            'orders' => ['delivery_price', 'subtotal', 'discount', 'total'],
            'order_items' => ['unit_price', 'total_price'],
            'order_item_options' => ['price'],
        ];

        foreach ($tables as $table => $columns) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            foreach ($columns as $column) {
                if (! Schema::hasColumn($table, $column)) {
                    continue;
                }

                DB::table($table)
                    ->whereNotNull($column)
                    ->update([
                        $column => DB::raw("ROUND({$column} * {$rate}, 2)"),
                    ]);
            }
        }
    }
};
