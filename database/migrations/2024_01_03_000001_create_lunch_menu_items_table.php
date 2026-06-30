<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lunch_menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('section');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_spicy')->default(false);
            $table->boolean('is_hit')->default(false);
            $table->boolean('is_new')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('lunch_menu_lunch_menu_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lunch_menu_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lunch_menu_item_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->unique(['lunch_menu_id', 'lunch_menu_item_id'], 'lunch_menu_item_pivot_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lunch_menu_lunch_menu_item');
        Schema::dropIfExists('lunch_menu_items');
    }
};
