<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lunch_menus', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('message')->nullable();
            $table->time('start_time');
            $table->time('end_time');
            $table->json('days_of_week');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('lunch_menu_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lunch_menu_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unique(['lunch_menu_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lunch_menu_product');
        Schema::dropIfExists('lunch_menus');
    }
};
