<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_settings', function (Blueprint $table) {
            $table->json('delivery_zone_polygon')->nullable()->after('delivery_radius_km');
            $table->decimal('delivery_inside_price', 10, 2)->default(2.00)->after('delivery_price');
            $table->decimal('delivery_outside_price', 10, 2)->default(3.00)->after('delivery_inside_price');
        });
    }

    public function down(): void
    {
        Schema::table('store_settings', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_zone_polygon',
                'delivery_inside_price',
                'delivery_outside_price',
            ]);
        });
    }
};
