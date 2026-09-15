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
            'Phones & Tablets' => 'Smartphones, tablets and mobile accessories from the brands Kenyans trust.',
            'Electronics' => 'TVs, audio, kitchen appliances and gadgets for the modern home.',
            'Home & Kitchen' => 'Cookware, décor and everyday essentials for the Kenyan household.',
            'Fashion' => 'Ankara, kitenge and everyday wear for men and women.',
            'Health & Beauty' => 'Skincare, haircare and personal care favourites.',
            'Supermarket' => 'Pantry staples and groceries delivered to your door.',
            'Computing' => 'Laptops, accessories and office essentials.',
            'Sports & Outdoors' => 'Gear for football, fitness and outdoor life.',
        ];

        foreach ($categories as $name => $description) {
            Category::query()->create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $description,
            ]);
        }
    }
}
