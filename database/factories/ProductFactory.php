<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'category_id' => Category::factory(),
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'short_description' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'base_price' => fake()->randomFloat(2, 8, 25),
            'old_price' => null,
            'image' => null,
            'is_active' => true,
            'is_featured' => false,
            'is_promo' => false,
            'is_new' => false,
            'is_spicy' => false,
            'sort_order' => fake()->numberBetween(1, 50),
            'seo_title' => null,
            'seo_description' => null,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Product $product) {
            $variants = [
                ['name' => 'Малка', 'size_label' => '25 см', 'diameter' => 25, 'multiplier' => 1.0],
                ['name' => 'Средна', 'size_label' => '30 см', 'diameter' => 30, 'multiplier' => 1.3],
                ['name' => 'Голяма', 'size_label' => '35 см', 'diameter' => 35, 'multiplier' => 1.6],
            ];

            foreach ($variants as $index => $variant) {
                ProductVariant::query()->create([
                    'product_id' => $product->id,
                    'name' => $variant['name'],
                    'size_label' => $variant['size_label'],
                    'diameter' => $variant['diameter'],
                    'price' => round((float) $product->base_price * $variant['multiplier'], 2),
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]);
            }
        });
    }

    public function featured(): static
    {
        return $this->state(fn () => [
            'is_featured' => true,
        ]);
    }
}
