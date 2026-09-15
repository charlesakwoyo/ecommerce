<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_categories_with_product_counts(): void
    {
        $category = Category::factory()->create(['name' => 'Electronics']);
        Product::factory()->count(3)->for($category)->create();

        $response = $this->getJson('/api/v1/categories');

        $response->assertOk();

        $payload = collect($response->json('data'))->firstWhere('id', $category->id);
        $this->assertSame(3, $payload['products_count']);
    }
}
