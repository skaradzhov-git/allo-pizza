<?php

namespace Database\Seeders;

use App\Enums\BannerPosition;
use App\Models\Banner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class BannerImageSeeder extends Seeder
{
    public function run(): void
    {
        $images = [
            1 => 'menu.jpg',
            2 => 'delivery.jpg',
            3 => 'promo-4-plus-1.jpg',
            4 => 'pickup.jpg',
            5 => 'delivery-zone.jpg',
        ];

        foreach ($images as $sortOrder => $filename) {
            $source = database_path("seeders/assets/banners/{$filename}");

            if (! is_file($source)) {
                continue;
            }

            $destination = "banners/{$filename}";
            Storage::disk('public')->put($destination, file_get_contents($source));

            Banner::query()
                ->where('position', BannerPosition::HomeSmallCards)
                ->where('sort_order', $sortOrder)
                ->update(['image' => $destination]);
        }
    }
}
