<?php

namespace App\Actions\Orders;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class CancelOrderAndReleaseStock
{
    public function handle(Order $order): Order
    {
        if (! in_array($order->status, [OrderStatus::Pending, OrderStatus::Paid], true)) {
            return $order;
        }

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $item->product?->increment('stock', $item->quantity);
            }

            $order->update(['status' => OrderStatus::Cancelled]);
        });

        return $order;
    }
}
