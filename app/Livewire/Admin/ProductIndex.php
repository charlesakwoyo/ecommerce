<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Products')]
class ProductIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function toggleActive(Product $product): void
    {
        $product->update(['is_active' => ! $product->is_active]);
    }

    public function delete(Product $product): void
    {
        $product->delete();
        session()->flash('status', "\"{$product->name}\" was deleted.");
    }

    public function render(): View
    {
        return view('livewire.admin.product-index', [
            'products' => Product::query()
                ->with('category')
                ->when($this->search !== '', fn ($query) => $query->where('name', 'like', "%{$this->search}%"))
                ->orderByDesc('created_at')
                ->paginate(15),
        ]);
    }
}
