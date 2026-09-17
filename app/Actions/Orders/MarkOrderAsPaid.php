<?php

namespace App\Actions\Orders;

use App\Enums\OrderStatus;
use App\Models\Order;

class MarkOrderAsPaid
{
    /**
     * @param  'card'|'mpesa'  $paymentMethod
     */
    public function handle(Order $order, string $paymentMethod, string $reference): Order
    {
        if ($order->status === OrderStatus::Paid) {
            return $order;
        }

        $order->update([
            'status' => OrderStatus::Paid,
            'payment_method' => $paymentMethod,
            'stripe_payment_intent_id' => $paymentMethod === 'card' ? $reference : $order->stripe_payment_intent_id,
            'mpesa_receipt_number' => $paymentMethod === 'mpesa' ? $reference : $order->mpesa_receipt_number,
            'paid_at' => now(),
        ]);

        return $order;
    }
}
