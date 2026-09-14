<?php

namespace App\Actions\Orders;

use App\Enums\OrderStatus;
use App\Models\Order;

class MarkOrderAsPaid
{
    public function handle(Order $order, string $paymentIntentId): Order
    {
        if ($order->status === OrderStatus::Paid) {
            return $order;
        }

        $order->update([
            'status' => OrderStatus::Paid,
            'stripe_payment_intent_id' => $paymentIntentId,
            'paid_at' => now(),
        ]);

        return $order;
    }
}
