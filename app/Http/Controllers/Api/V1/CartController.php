<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\OutOfStockException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AddCartItemRequest;
use App\Http\Requests\Api\V1\UpdateCartItemRequest;
use App\Http\Resources\CartResource;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    /**
     * Show the authenticated user's cart.
     */
    public function show(CartService $cartService): CartResource
    {
        $cart = $cartService->current();
        $cart->load('items.product.images');

        return new CartResource($cart);
    }

    /**
     * Add a product to the authenticated user's cart.
     */
    public function store(AddCartItemRequest $request, CartService $cartService): CartResource|JsonResponse
    {
        $product = Product::query()->findOrFail($request->validated('product_id'));

        try {
            $cartService->add($product, $request->validated('quantity'));
        } catch (OutOfStockException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }

        $cart = $cartService->current();
        $cart->load('items.product.images');

        return new CartResource($cart);
    }

    /**
     * Update the quantity of a cart item belonging to the authenticated user.
     */
    public function update(UpdateCartItemRequest $request, CartItem $cartItem, CartService $cartService): CartResource|JsonResponse
    {
        $this->authorizeOwnership($cartItem);

        try {
            $cartService->updateQuantity($cartItem, $request->validated('quantity'));
        } catch (OutOfStockException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }

        $cart = $cartService->current();
        $cart->load('items.product.images');

        return new CartResource($cart);
    }

    /**
     * Remove an item from the authenticated user's cart.
     */
    public function destroy(CartItem $cartItem, CartService $cartService): CartResource
    {
        $this->authorizeOwnership($cartItem);

        $cartService->remove($cartItem);

        $cart = $cartService->current();
        $cart->load('items.product.images');

        return new CartResource($cart);
    }

    private function authorizeOwnership(CartItem $cartItem): void
    {
        abort_unless($cartItem->cart->user_id === auth()->id(), 403);
    }
}
