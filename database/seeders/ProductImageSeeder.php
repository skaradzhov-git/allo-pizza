<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $images = [
            'margarita' => 'margarita.png',
            'peperoni' => 'peperoni.png',
            '4-sirena' => '4-sirena.png',
            'kaprichoza' => 'kaprichoza.png',
            'krudo' => 'krudo.png',
            'chikan' => 'chikan.png',
            'elena' => 'elena.png',
            'redzhina' => 'redzhina.png',
            'barbekyu' => 'barbekyu.png',
            'italianski-sandvich' => 'italianski-sandvich.png',
            'kapreze-sandvich' => 'kapreze-sandvich.png',
            'parlenka-sas-sirene' => 'parlenka-sas-sirene.png',
            'parlenka-s-maslo' => 'parlenka-sas-sirene.png',
            'parlenka-s-kashkaval' => 'parlenka-sas-sirene.png',
            'chesnova-parlenka' => 'parlenka-sas-sirene.png',
            'parlenka-kombinirana' => 'parlenka-kombinirana.png',
            'koka-kola-500' => 'koka-kola-500.png',
            'mineralna-voda-500' => 'mineralna-voda-500.png',
            'ayryan-500' => 'ayryan-500.png',
            'domashna-limonada' => 'domashna-limonada.png',
        ];

        foreach ($images as $slug => $filename) {
            $source = database_path("seeders/assets/products/{$filename}");

            if (! is_file($source)) {
                continue;
            }

            $destination = "products/{$filename}";
            Storage::disk('public')->put($destination, file_get_contents($source));

            Product::query()
                ->where('slug', $slug)
                ->update(['image' => $destination]);
        }
    }
}
