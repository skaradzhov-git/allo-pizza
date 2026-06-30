<?php

use App\Models\StoreSetting;
use App\Support\DeliveryZone;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        StoreSetting::query()->update([
            'delivery_zone_polygon' => json_encode(DeliveryZone::defaultPolygon()),
        ]);
    }

    public function down(): void
    {
        // No rollback.
    }
};
