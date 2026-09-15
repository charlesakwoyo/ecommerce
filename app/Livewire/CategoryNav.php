<?php

namespace App\Livewire;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CategoryNav extends Component
{
    public function render(): View
    {
        return view('livewire.category-nav', [
            'categories' => Category::query()->whereNull('parent_id')->orderBy('name')->take(8)->get(),
        ]);
    }
}
