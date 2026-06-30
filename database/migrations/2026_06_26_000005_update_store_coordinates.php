<?php

use App\Models\StoreSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        StoreSetting::query()->update([
            'store_lat' => 43.8407475,
            'store_lng' => 25.9549665,
        ]);
    }

    public function down(): void
    {
        StoreSetting::query()->update([
            'store_lat' => 43.8407468,
            'store_lng' => 25.9536970,
        ]);
    }
};
