<?php

namespace App\View\Components;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class FooterCategories extends Component
{
    public Collection $categories;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->categories = Category::query()->whereNull('parent_id')->orderBy('name')->take(6)->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.footer-categories');
    }
}
