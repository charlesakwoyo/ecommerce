<?php

namespace App\Livewire\Storefront;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Shop the latest products')]
class Home extends Component
{
    public function render(): View
    {
        return view('livewire.storefront.home', [
            'categories' => Category::query()->withCount('products')->orderBy('name')->get(),
            'products' => Product::query()
                ->active()
                ->with('images')
                ->latest()
                ->take(8)
                ->get(),
        ]);
    }
}
