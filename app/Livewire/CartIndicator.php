<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

class CartIndicator extends Component
{
    public int $count = 0;

    public function mount(CartService $cart): void
    {
        $this->count = $cart->current()->totalQuantity();
    }

    #[On('cart-updated')]
    public function refreshCount(CartService $cart): void
    {
        $this->count = $cart->current()->totalQuantity();
    }

    public function render()
    {
        return view('livewire.cart-indicator');
    }
}
