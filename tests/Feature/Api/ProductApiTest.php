<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_only_active_products(): void
    {
        Product::factory()->create(['name' => 'Visible Product', 'is_active' => true]);
        Product::factory()->create(['name' => 'Hidden Product', 'is_active' => false]);

        $response = $this->getJson('/api/v1/products');

        $response->assertOk();
        $names = collect($response->json('data'))->pluck('name');

        $this->assertTrue($names->contains('Visible Product'));
        $this->assertFalse($names->contains('Hidden Product'));
    }

    public function test_it_filters_products_by_search_term(): void
    {
        Product::factory()->create(['name' => 'Samsung Galaxy A15']);
        Product::factory()->create(['name' => 'Kabras Sugar 2kg']);

        $response = $this->getJson('/api/v1/products?q=Samsung');

        $response->assertOk();
        $names = collect($response->json('data'))->pluck('name');

        $this->assertTrue($names->contains('Samsung Galaxy A15'));
        $this->assertFalse($names->contains('Kabras Sugar 2kg'));
    }

    public function test_it_filters_products_by_category(): void
    {
        $electronics = Category::factory()->create(['slug' => 'electronics']);
        $fashion = Category::factory()->create(['slug' => 'fashion']);

        Product::factory()->for($electronics)->create(['name' => 'TV']);
        Product::factory()->for($fashion)->create(['name' => 'Dress']);

        $response = $this->getJson('/api/v1/products?category=electronics');

        $response->assertOk();
        $names = collect($response->json('data'))->pluck('name');

        $this->assertTrue($names->contains('TV'));
        $this->assertFalse($names->contains('Dress'));
    }

    public function test_it_shows_a_single_product_by_slug(): void
    {
        $product = Product::factory()->create(['name' => 'Yoga Mat', 'slug' => 'yoga-mat']);

        $response = $this->getJson('/api/v1/products/yoga-mat');

        $response->assertOk()->assertJsonPath('data.id', $product->id);
    }

    public function test_it_returns_404_for_an_unknown_product_slug(): void
    {
        $response = $this->getJson('/api/v1/products/does-not-exist');

        $response->assertNotFound();
    }
}
