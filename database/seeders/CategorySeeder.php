<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categories = [
            'Electronics',
            'Home & Kitchen',
            'Clothing & Accessories',
            'Books',
            'Sports & Outdoors',
            'Toys & Games',
        ];

        foreach ($categories as $name) {
            Category::query()->create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => fake()->sentence(),
            ]);
        }
    }
}
