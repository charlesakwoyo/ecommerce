<?php

namespace App\Livewire\Admin;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Orders')]
class OrderIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $status = '';

    public function render(): View
    {
        return view('livewire.admin.order-index', [
            'orders' => Order::query()
                ->with('user')
                ->when($this->status !== '', fn ($query) => $query->where('status', $this->status))
                ->orderByDesc('created_at')
                ->paginate(15),
            'statuses' => OrderStatus::cases(),
        ]);
    }
}
