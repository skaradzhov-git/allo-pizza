<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_settings', function (Blueprint $table) {
            $table->id();
            $table->string('store_name');
            $table->string('store_phone')->nullable();
            $table->string('store_email')->nullable();
            $table->string('store_address')->nullable();
            $table->decimal('store_lat', 10, 7)->nullable();
            $table->decimal('store_lng', 10, 7)->nullable();
            $table->decimal('delivery_radius_km', 8, 2)->default(5);
            $table->decimal('delivery_price', 10, 2)->default(0);
            $table->decimal('free_delivery_over', 10, 2)->nullable();
            $table->decimal('minimum_order_amount', 10, 2)->default(0);
            $table->unsignedInteger('average_delivery_time')->nullable();
            $table->boolean('is_store_open')->default(true);
            $table->text('closed_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_settings');
    }
};
