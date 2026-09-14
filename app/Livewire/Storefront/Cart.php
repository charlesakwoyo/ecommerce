<?php

namespace App\Livewire\Storefront;

use App\Exceptions\OutOfStockException;
use App\Models\CartItem;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Your cart')]
class Cart extends Component
{
    public function updateQuantity(CartItem $item, int $quantity, CartService $cart): void
    {
        abort_unless($item->cart_id === $cart->current()->id, 403);

        try {
            $cart->updateQuantity($item, $quantity);
        } catch (OutOfStockException $exception) {
            $this->addError('cart', $exception->getMessage());

            return;
        }

        $this->dispatch('cart-updated');
    }

    public function remove(CartItem $item, CartService $cart): void
    {
        abort_unless($item->cart_id === $cart->current()->id, 403);

        $cart->remove($item);
        $this->dispatch('cart-updated');
    }

    public function render(): View
    {
        $cart = app(CartService::class)->current();
        $cart->load(['items.product.images']);

        return view('livewire.storefront.cart', [
            'cart' => $cart,
        ]);
    }
}
