<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_categories_api_returns_active_categories(): void
    {
        Category::factory()->create(['name' => 'Пица', 'slug' => 'pizza', 'is_active' => true]);
        Category::factory()->create(['name' => 'Скрита', 'slug' => 'hidden', 'is_active' => false]);

        $response = $this->getJson('/api/categories');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'pizza');
    }

    public function test_products_api_returns_active_products(): void
    {
        $category = Category::factory()->create();
        Product::factory()->create(['category_id' => $category->id, 'is_active' => true]);
        Product::factory()->create(['category_id' => $category->id, 'is_active' => false]);

        $response = $this->getJson('/api/products');

        $response->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_home_page_loads(): void
    {
        $response = $this->get('/');

        $response->assertOk();
    }
}
