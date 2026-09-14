<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::all()->each(function (Category $category) {
            Product::factory()
                ->count(fake()->numberBetween(4, 8))
                ->for($category)
                ->create()
                ->each(function (Product $product) {
                    $product->images()->create([
                        'path' => 'https://picsum.photos/seed/'.$product->id.'/600/600',
                        'sort_order' => 0,
                    ]);
                });
        });
    }
}
