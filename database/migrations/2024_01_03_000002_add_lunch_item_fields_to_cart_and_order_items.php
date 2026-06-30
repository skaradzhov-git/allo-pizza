<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->string('item_type')->default('product')->after('cart_id');
            $table->foreignId('lunch_menu_item_id')->nullable()->after('product_variant_id')->constrained()->nullOnDelete();
            $table->string('item_name')->nullable()->after('lunch_menu_item_id');
            $table->text('item_description')->nullable()->after('item_name');
            $table->string('item_image')->nullable()->after('item_description');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE cart_items MODIFY product_id BIGINT UNSIGNED NULL');
        }

        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->string('item_type')->default('product')->after('order_id');
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['lunch_menu_item_id']);
            $table->dropColumn(['item_type', 'lunch_menu_item_id', 'item_name', 'item_description', 'item_image']);
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE cart_items MODIFY product_id BIGINT UNSIGNED NOT NULL');
        }

        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('item_type');
        });
    }
};
