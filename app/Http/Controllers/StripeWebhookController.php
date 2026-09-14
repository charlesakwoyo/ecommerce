<?php

namespace App\Http\Controllers;

use App\Actions\Orders\CancelOrderAndReleaseStock;
use App\Actions\Orders\MarkOrderAsPaid;
use App\Models\Order;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;
use Symfony\Component\HttpFoundation\Response;

class StripeWebhookController extends CashierWebhookController
{
    /**
     * Mark the matching order as paid once Stripe confirms the checkout session completed.
     */
    protected function handleCheckoutSessionCompleted(array $payload): Response
    {
        $session = $payload['data']['object'];
        $orderId = $session['metadata']['order_id'] ?? null;

        if ($orderId && $order = Order::query()->find($orderId)) {
            app(MarkOrderAsPaid::class)->handle($order, (string) $session['payment_intent']);
        }

        return $this->successMethod();
    }

    /**
     * Release reserved stock when a checkout session expires without payment.
     */
    protected function handleCheckoutSessionExpired(array $payload): Response
    {
        $session = $payload['data']['object'];
        $orderId = $session['metadata']['order_id'] ?? null;

        if ($orderId && $order = Order::query()->find($orderId)) {
            app(CancelOrderAndReleaseStock::class)->handle($order);
        }

        return $this->successMethod();
    }
}
