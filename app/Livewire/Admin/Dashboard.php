<?php

namespace App\Livewire\Admin;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    public function render(): View
    {
        return view('livewire.admin.dashboard', [
            'productCount' => Product::query()->count(),
            'lowStockCount' => Product::query()->where('stock', '<=', 5)->count(),
            'pendingOrderCount' => Order::query()->where('status', OrderStatus::Pending)->count(),
            'revenue' => Order::query()->where('status', OrderStatus::Paid)->sum('total'),
            'recentOrders' => Order::query()->with('user')->latest()->take(5)->get(),
        ]);
    }
}
