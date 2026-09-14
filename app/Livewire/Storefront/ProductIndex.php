<?php

namespace App\Livewire\Storefront;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Shop')]
class ProductIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $category = '';

    public function updating(string $property): void
    {
        if (in_array($property, ['search', 'category'], true)) {
            $this->resetPage();
        }
    }

    public function render(): View
    {
        $products = Product::query()
            ->active()
            ->with('images')
            ->when($this->search !== '', fn ($query) => $query->where('name', 'like', "%{$this->search}%"))
            ->when($this->category !== '', fn ($query) => $query->whereRelation('category', 'slug', $this->category))
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(12);

        return view('livewire.storefront.product-index', [
            'products' => $products,
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }
}
