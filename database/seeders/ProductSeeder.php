<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Kenyan-market product catalogue, grouped by category name.
     *
     * @var array<string, array<int, array{name: string, price: int, description: string}>>
     */
    private array $catalogue = [
        'Phones & Tablets' => [
            ['name' => 'Samsung Galaxy A15', 'price' => 18999, 'description' => 'A budget-friendly Android smartphone with a 6.5" display, 128GB storage and a 50MP camera.'],
            ['name' => 'Tecno Spark 20', 'price' => 14500, 'description' => 'Reliable everyday smartphone with a large battery and dual rear camera, built for the Kenyan market.'],
            ['name' => 'Infinix Hot 40i', 'price' => 12999, 'description' => 'Affordable smartphone with 128GB storage, ideal for social media and everyday browsing.'],
            ['name' => 'Xiaomi Redmi Note 13', 'price' => 22999, 'description' => 'Mid-range powerhouse with a 108MP camera and fast charging.'],
            ['name' => 'Apple iPhone 13 (128GB)', 'price' => 74999, 'description' => 'Apple\'s A15 Bionic chip, Super Retina XDR display and all-day battery life.'],
            ['name' => 'Oraimo FreePods 4', 'price' => 2499, 'description' => 'True wireless earbuds with active noise cancellation and 30-hour battery life.'],
            ['name' => 'Anker PowerCore 20000mAh', 'price' => 3999, 'description' => 'High-capacity power bank to keep your devices charged on the go.'],
        ],
        'Electronics' => [
            ['name' => 'Samsung 43" Smart TV', 'price' => 32999, 'description' => 'Full HD Smart TV with built-in Wi-Fi and access to your favourite streaming apps.'],
            ['name' => 'Von Hotpoint Blender 1.5L', 'price' => 4500, 'description' => 'Durable kitchen blender for smoothies, soups and sauces.'],
            ['name' => 'LG 20L Microwave', 'price' => 9999, 'description' => 'Compact microwave oven with grill function, perfect for small kitchens.'],
            ['name' => 'Sony Bluetooth Party Speaker', 'price' => 6999, 'description' => 'Portable speaker with deep bass and up to 12 hours of playtime.'],
            ['name' => 'Solar Rechargeable Lantern', 'price' => 1899, 'description' => 'Reliable solar-powered lighting for homes without stable power.'],
            ['name' => 'Hisense Double Door Fridge', 'price' => 45999, 'description' => 'Energy-efficient double door refrigerator with generous storage space.'],
        ],
        'Home & Kitchen' => [
            ['name' => 'Non-Stick Sufuria Set (7pc)', 'price' => 5499, 'description' => 'Durable non-stick cookware set for everyday Kenyan cooking.'],
            ['name' => 'Kiondo Woven Sisal Basket', 'price' => 1200, 'description' => 'Handwoven sisal basket, perfect for shopping or home décor.'],
            ['name' => 'Maasai Beaded Coasters (Set of 6)', 'price' => 899, 'description' => 'Colourful handmade beaded coasters crafted by local artisans.'],
            ['name' => 'Ramtons Pressure Cooker 8L', 'price' => 6999, 'description' => 'Large capacity pressure cooker, ideal for family meals.'],
            ['name' => 'Vacuum Flask 1.5L', 'price' => 1499, 'description' => 'Keeps beverages hot or cold for hours, great for tea and travel.'],
            ['name' => 'Executive Duvet Set (4pc)', 'price' => 3999, 'description' => 'Soft, breathable duvet set with matching pillowcases.'],
        ],
        'Fashion' => [
            ['name' => 'Maasai Shuka Blanket', 'price' => 1499, 'description' => 'Authentic checkered Maasai shuka, versatile as a wrap or throw.'],
            ['name' => 'Ankara Print Maxi Dress', 'price' => 2999, 'description' => 'Vibrant Ankara print dress, tailored for a flattering fit.'],
            ['name' => 'Kitenge Fabric (6 Yards)', 'price' => 1800, 'description' => 'Premium kitenge fabric bundle, ready for your next tailoring project.'],
            ['name' => "Men's Kitenge Shirt", 'price' => 2200, 'description' => 'Smart-casual short-sleeve shirt in bold kitenge print.'],
            ['name' => 'Handmade Leather Sandals', 'price' => 1999, 'description' => 'Genuine leather sandals, handcrafted by local cobblers.'],
            ['name' => 'Kenyan Safari Hat', 'price' => 999, 'description' => 'Wide-brim hat for sun protection on outdoor adventures.'],
        ],
        'Health & Beauty' => [
            ['name' => 'Nivea Body Lotion 400ml', 'price' => 699, 'description' => 'Nourishing body lotion for 48-hour moisture.'],
            ['name' => 'Dark & Lovely Relaxer Kit', 'price' => 899, 'description' => 'Gentle relaxer kit for smooth, manageable hair.'],
            ['name' => 'Kenyan Aloe Vera Gel 500ml', 'price' => 799, 'description' => 'Pure aloe vera gel, locally sourced and cold-pressed.'],
            ['name' => 'Shea Butter Moisturizer', 'price' => 649, 'description' => 'Rich, unrefined shea butter for deep skin hydration.'],
            ['name' => 'Electric Hair Clipper', 'price' => 2499, 'description' => 'Rechargeable hair clipper with multiple guide combs.'],
        ],
        'Supermarket' => [
            ['name' => 'Ketepa Tea Leaves 500g', 'price' => 350, 'description' => 'Kenya\'s favourite tea leaves, grown in the highlands.'],
            ['name' => 'Pembe Maize Flour 2kg', 'price' => 220, 'description' => 'Finely milled maize flour for ugali and porridge.'],
            ['name' => 'Kabras Sugar 2kg', 'price' => 320, 'description' => 'Pure white sugar, locally refined.'],
            ['name' => 'Golden Fry Cooking Oil 3L', 'price' => 899, 'description' => 'Cholesterol-free vegetable cooking oil for everyday meals.'],
            ['name' => 'Brookside Fresh Milk 500ml (6-Pack)', 'price' => 480, 'description' => 'Fresh pasteurized milk, pack of six 500ml packets.'],
            ['name' => 'Kenya AA Coffee Beans 250g', 'price' => 650, 'description' => 'Premium AA-grade coffee beans from Kenyan highland farms.'],
        ],
        'Computing' => [
            ['name' => 'HP 15 Laptop (Core i5, 8GB/512GB)', 'price' => 62999, 'description' => 'Reliable laptop for work and study with fast SSD storage.'],
            ['name' => 'Logitech Wireless Mouse', 'price' => 1499, 'description' => 'Ergonomic wireless mouse with long battery life.'],
            ['name' => 'Dell 24" Monitor', 'price' => 15999, 'description' => 'Full HD monitor with slim bezels, great for productivity.'],
            ['name' => '1TB External Hard Drive', 'price' => 5999, 'description' => 'Portable storage for backups, photos and files.'],
            ['name' => 'Wireless Keyboard & Mouse Combo', 'price' => 2299, 'description' => 'Clutter-free desk setup with a matching keyboard and mouse.'],
        ],
        'Sports & Outdoors' => [
            ['name' => 'Official Size 5 Football', 'price' => 1999, 'description' => 'Match-quality football, built to last on any pitch.'],
            ['name' => 'Running Shoes (Unisex)', 'price' => 3499, 'description' => 'Lightweight, breathable running shoes for road or track.'],
            ['name' => 'Yoga Mat', 'price' => 1299, 'description' => 'Non-slip yoga mat, ideal for home workouts.'],
            ['name' => 'Gym Duffel Bag', 'price' => 1799, 'description' => 'Spacious duffel bag with shoe compartment for the gym.'],
            ['name' => 'Skipping Rope', 'price' => 499, 'description' => 'Adjustable speed rope for cardio and conditioning.'],
        ],
    ];

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach ($this->catalogue as $categoryName => $products) {
            $category = Category::query()->where('slug', Str::slug($categoryName))->first();

            if (! $category) {
                continue;
            }

            foreach ($products as $item) {
                $product = Product::query()->create([
                    'category_id' => $category->id,
                    'name' => $item['name'],
                    'slug' => Str::slug($item['name']),
                    'description' => $item['description'],
                    'price' => $item['price'] * 100,
                    'stock' => fake()->numberBetween(0, 60),
                    'sku' => strtoupper(Str::random(8)),
                    'is_active' => true,
                ]);

                $imagePath = 'products/'.$product->slug.'.svg';
                Storage::disk('public')->put($imagePath, $this->placeholderSvg($item['name']));

                $product->images()->create([
                    'path' => $imagePath,
                    'sort_order' => 0,
                ]);
            }
        }
    }

    /**
     * A locally generated placeholder image that displays the exact product
     * name, so every image visibly and reliably matches its listing without
     * depending on an external image host.
     */
    private function placeholderSvg(string $name): string
    {
        $words = explode(' ', $name);
        $lines = [];
        $current = '';

        foreach ($words as $word) {
            $candidate = trim($current.' '.$word);

            if (mb_strlen($candidate) > 16 && $current !== '') {
                $lines[] = $current;
                $current = $word;
            } else {
                $current = $candidate;
            }
        }

        if ($current !== '') {
            $lines[] = $current;
        }

        $fontSize = 40;
        $lineHeight = $fontSize * 1.25;
        $startY = 300 - ($lineHeight * (count($lines) - 1) / 2);

        $tspans = '';
        foreach ($lines as $index => $line) {
            $y = (int) ($startY + $index * $lineHeight);
            $tspans .= '<tspan x="300" y="'.$y.'">'.e($line).'</tspan>';
        }

        return <<<SVG
        <svg xmlns="http://www.w3.org/2000/svg" width="600" height="600" viewBox="0 0 600 600">
            <rect width="600" height="600" fill="#f68b1e" />
            <text text-anchor="middle" fill="#ffffff" font-family="Arial, Helvetica, sans-serif" font-size="{$fontSize}" font-weight="700">{$tspans}</text>
        </svg>
        SVG;
    }
}
