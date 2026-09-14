<?php

namespace Tests\Feature;

use App\Livewire\Storefront\ProductIndex;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProductBrowsingTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_product_index_only_lists_active_products(): void
    {
        $active = Product::factory()->create(['name' => 'Visible Widget']);
        $inactive = Product::factory()->inactive()->create(['name' => 'Hidden Widget']);

        $response = $this->get(route('products.index'));

        $response->assertOk();
        $response->assertSee('Visible Widget');
        $response->assertDontSee('Hidden Widget');
    }

    public function test_product_index_can_filter_by_category(): void
    {
        $shirts = Category::factory()->create(['name' => 'Shirts', 'slug' => 'shirts']);
        $shoes = Category::factory()->create(['name' => 'Shoes', 'slug' => 'shoes']);

        Product::factory()->for($shirts)->create(['name' => 'Blue Shirt']);
        Product::factory()->for($shoes)->create(['name' => 'Running Shoe']);

        Livewire::test(ProductIndex::class)
            ->set('category', 'shirts')
            ->assertSee('Blue Shirt')
            ->assertDontSee('Running Shoe');
    }

    public function test_product_show_page_displays_product_details(): void
    {
        $product = Product::factory()->create(['name' => 'Premium Widget']);

        $response = $this->get(route('products.show', $product));

        $response->assertOk();
        $response->assertSee('Premium Widget');
    }
}
