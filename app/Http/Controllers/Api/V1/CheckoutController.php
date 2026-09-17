<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Orders\CreateOrderFromCart;
use App\Enums\AddressType;
use App\Exceptions\OutOfStockException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\PlaceOrderRequest;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;

class CheckoutController extends Controller
{
    /**
     * Reserve stock, create a pending order from the authenticated user's
     * cart, and return a Stripe Checkout URL for the client to redirect to.
     */
    public function store(PlaceOrderRequest $request, CartService $cartService, CreateOrderFromCart $createOrder): JsonResponse
    {
        $cart = $cartService->current();
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty.'], 422);
        }

        $user = $request->user();

        $address = $request->validated('address_id')
            ? $user->addresses()->findOrFail($request->validated('address_id'))
            : $user->addresses()->create([
                'type' => AddressType::Shipping,
                ...collect($request->validated('address'))->only([
                    'name', 'line1', 'line2', 'city', 'state', 'postal_code', 'phone',
                ])->all(),
                'country' => strtoupper($request->validated('address.country')),
                'is_default' => $user->addresses()->doesntExist(),
            ]);

        try {
            $order = $createOrder->handle($cart, $user, $address);
        } catch (OutOfStockException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }

        $checkout = $user->checkout($order->stripeLineItems(), [
            'success_url' => $request->validated('success_url'),
            'cancel_url' => $request->validated('cancel_url'),
            'metadata' => ['order_id' => $order->id],
        ]);

        $order->update(['stripe_checkout_session_id' => $checkout->id]);

        return response()->json([
            'order_id' => $order->id,
            'checkout_url' => $checkout->url,
        ], 201);
    }
}
