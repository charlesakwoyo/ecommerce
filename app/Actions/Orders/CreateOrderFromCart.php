<?php

namespace App\Actions\Orders;

use App\Enums\OrderStatus;
use App\Exceptions\OutOfStockException;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateOrderFromCart
{
    /**
     * Create a pending order from the given cart, reserving stock for each item.
     *
     * @throws OutOfStockException
     */
    public function handle(Cart $cart, User $user, Address $shippingAddress, ?Address $billingAddress = null): Order
    {
        return DB::transaction(function () use ($cart, $user, $shippingAddress, $billingAddress) {
            $items = $cart->items()->with('product')->get();

            $subtotal = 0;

            $order = Order::query()->create([
                'user_id' => $user->id,
                'order_number' => $this->generateOrderNumber(),
                'status' => OrderStatus::Pending,
                'subtotal' => 0,
                'tax' => 0,
                'shipping' => 0,
                'total' => 0,
                'currency' => config('cashier.currency'),
                'shipping_address' => $shippingAddress->toSnapshot(),
                'billing_address' => ($billingAddress ?? $shippingAddress)->toSnapshot(),
            ]);

            foreach ($items as $item) {
                /** @var Product $product */
                $product = Product::query()->whereKey($item->product_id)->lockForUpdate()->firstOrFail();

                if ($item->quantity > $product->stock) {
                    throw new OutOfStockException($product, $product->stock);
                }

                $product->decrement('stock', $item->quantity);

                $order->items()->create([
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'unit_price' => $product->price,
                    'quantity' => $item->quantity,
                ]);

                $subtotal += $product->price * $item->quantity;
            }

            $order->update([
                'subtotal' => $subtotal,
                'total' => $subtotal + $order->tax + $order->shipping,
            ]);

            $cart->items()->delete();

            return $order->fresh('items');
        });
    }

    private function generateOrderNumber(): string
    {
        return 'ORD-'.strtoupper(Str::random(10));
    }
}
