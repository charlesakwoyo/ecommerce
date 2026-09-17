<?php

namespace App\Http\Controllers;

use App\Actions\Orders\CancelOrderAndReleaseStock;
use App\Actions\Orders\MarkOrderAsPaid;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MpesaCallbackController extends Controller
{
    /**
     * Handle the asynchronous STK push result Safaricom posts back once the
     * customer has responded (or the prompt times out).
     */
    public function handle(Request $request): JsonResponse
    {
        $callback = $request->input('Body.stkCallback', []);
        $checkoutRequestId = $callback['CheckoutRequestID'] ?? null;

        $order = $checkoutRequestId
            ? Order::query()->where('mpesa_checkout_request_id', $checkoutRequestId)->first()
            : null;

        if ($order && (int) ($callback['ResultCode'] ?? 1) === 0) {
            $metadata = collect($callback['CallbackMetadata']['Item'] ?? [])
                ->pluck('Value', 'Name');

            app(MarkOrderAsPaid::class)->handle(
                $order,
                'mpesa',
                (string) $metadata->get('MpesaReceiptNumber', $checkoutRequestId),
            );
        } elseif ($order) {
            app(CancelOrderAndReleaseStock::class)->handle($order);
        }

        // Safaricom expects this exact acknowledgement shape regardless of outcome.
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }
}
