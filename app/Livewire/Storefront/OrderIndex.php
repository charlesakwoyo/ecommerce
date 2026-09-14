<?php

namespace App\Livewire\Storefront;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('My orders')]
class OrderIndex extends Component
{
    use WithPagination;

    public function render(): View
    {
        return view('livewire.storefront.order-index', [
            'orders' => Order::query()
                ->forUser(auth()->user())
                ->orderByDesc('created_at')
                ->paginate(10),
        ]);
    }
}
