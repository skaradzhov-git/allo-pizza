<?php

use App\Models\StoreSetting;
use App\Support\DeliveryZone;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        StoreSetting::query()
            ->whereNull('delivery_zone_polygon')
            ->orWhere('delivery_zone_polygon', '[]')
            ->update([
                'delivery_zone_polygon' => json_encode(DeliveryZone::defaultPolygon()),
            ]);
    }

    public function down(): void
    {
        // No rollback — polygon data is intentional.
    }
};
