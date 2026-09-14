<?php

namespace App\Livewire\Storefront;

use App\Exceptions\OutOfStockException;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class ProductShow extends Component
{
    public Product $product;

    public int $quantity = 1;

    public function mount(Product $product): void
    {
        $this->product = $product;
    }

    public function addToCart(CartService $cart): void
    {
        $this->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:'.max($this->product->stock, 1)],
        ]);

        try {
            $cart->add($this->product, $this->quantity);
        } catch (OutOfStockException $exception) {
            $this->addError('quantity', $exception->getMessage());

            return;
        }

        $this->dispatch('cart-updated');
        session()->flash('status', "{$this->product->name} added to your cart.");
    }

    public function render(): View
    {
        return view('livewire.storefront.product-show', [
            'related' => Product::query()
                ->active()
                ->with('images')
                ->where('category_id', $this->product->category_id)
                ->whereKeyNot($this->product->id)
                ->take(4)
                ->get(),
        ])->title($this->product->name);
    }
}
