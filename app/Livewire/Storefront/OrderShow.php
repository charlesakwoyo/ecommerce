<?php

namespace App\Livewire\Storefront;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Order details')]
class OrderShow extends Component
{
    public Order $order;

    public function mount(Order $order): void
    {
        $this->authorize('view', $order);

        $this->order = $order->load('items');
    }

    public function render(): View
    {
        return view('livewire.storefront.order-show');
    }
}
