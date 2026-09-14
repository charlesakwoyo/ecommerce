<?php

namespace App\Services;

use App\Exceptions\OutOfStockException;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CartService
{
    private const SESSION_KEY = 'cart_session_id';

    public function current(): Cart
    {
        if ($user = auth()->user()) {
            return Cart::query()->firstOrCreate(['user_id' => $user->id]);
        }

        return Cart::query()->firstOrCreate(['session_id' => $this->guestSessionId()]);
    }

    public function add(Product $product, int $quantity = 1): CartItem
    {
        $cart = $this->current();

        $item = $cart->items()->firstOrNew(['product_id' => $product->id]);
        $desiredQuantity = ($item->exists ? $item->quantity : 0) + $quantity;

        if ($desiredQuantity > $product->stock) {
            throw new OutOfStockException($product, $product->stock);
        }

        $item->quantity = $desiredQuantity;
        $item->unit_price = $product->price;
        $item->save();

        return $item->fresh();
    }

    public function updateQuantity(CartItem $item, int $quantity): CartItem
    {
        if ($quantity < 1) {
            $this->remove($item);

            return $item;
        }

        $product = $item->product;

        if ($quantity > $product->stock) {
            throw new OutOfStockException($product, $product->stock);
        }

        $item->update([
            'quantity' => $quantity,
            'unit_price' => $product->price,
        ]);

        return $item;
    }

    public function remove(CartItem $item): void
    {
        $item->delete();
    }

    public function clear(Cart $cart): void
    {
        $cart->items()->delete();
    }

    /**
     * Merge a guest's session cart into their user cart after login.
     */
    public function mergeGuestCartIntoUser(User $user): void
    {
        $sessionId = Session::get(self::SESSION_KEY);

        if (! $sessionId) {
            return;
        }

        $guestCart = Cart::query()->where('session_id', $sessionId)->first();

        if (! $guestCart) {
            return;
        }

        $userCart = Cart::query()->firstOrCreate(['user_id' => $user->id]);

        foreach ($guestCart->items as $guestItem) {
            $existing = $userCart->items()->firstOrNew(['product_id' => $guestItem->product_id]);
            $mergedQuantity = ($existing->exists ? $existing->quantity : 0) + $guestItem->quantity;

            $existing->quantity = min($mergedQuantity, $guestItem->product->stock);
            $existing->unit_price = $guestItem->product->price;
            $existing->save();
        }

        $guestCart->delete();
        Session::forget(self::SESSION_KEY);
    }

    private function guestSessionId(): string
    {
        if (! Session::has(self::SESSION_KEY)) {
            Session::put(self::SESSION_KEY, (string) Str::uuid());
        }

        return Session::get(self::SESSION_KEY);
    }
}
