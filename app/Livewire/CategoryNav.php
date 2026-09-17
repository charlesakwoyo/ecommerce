<?php

namespace App\Livewire;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CategoryNav extends Component
{
    public function render(): View
    {
        $categories = Category::query()
            ->whereNull('parent_id')
            ->withCount('products')
            ->with(['children' => fn ($query) => $query->orderBy('name')])
            ->orderBy('name')
            ->get();

        return view('livewire.category-nav', [
            'allCategories' => $categories,
            'quickLinks' => $categories->take(8),
        ]);
    }
}
