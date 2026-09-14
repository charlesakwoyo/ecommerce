<?php

namespace App\Livewire\Admin;

use App\Actions\Orders\CancelOrderAndReleaseStock;
use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class OrderShow extends Component
{
    public Order $order;

    public string $status = '';

    public function mount(Order $order): void
    {
        $this->order = $order->load('items', 'user');
        $this->status = $order->status->value;
    }

    public function updateStatus(CancelOrderAndReleaseStock $cancelAction): void
    {
        $newStatus = OrderStatus::from($this->status);

        if ($newStatus === OrderStatus::Cancelled) {
            $this->order = $cancelAction->handle($this->order);
        } else {
            $this->order->update(['status' => $newStatus]);
        }

        $this->order->refresh();
        $this->status = $this->order->status->value;

        session()->flash('status', 'Order status updated.');
    }

    public function render(): View
    {
        return view('livewire.admin.order-show', [
            'statuses' => OrderStatus::cases(),
        ])->title('Order '.$this->order->order_number);
    }
}
