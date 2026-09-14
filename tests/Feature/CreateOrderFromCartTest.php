<?php

namespace Tests\Feature;

use App\Actions\Orders\CreateOrderFromCart;
use App\Enums\OrderStatus;
use App\Exceptions\OutOfStockException;
use App\Models\Address;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateOrderFromCartTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_an_order_and_decrements_stock(): void
    {
        $user = User::factory()->create();
        $address = Address::factory()->for($user)->create();
        $product = Product::factory()->create(['stock' => 5, 'price' => 1000]);

        $this->actingAs($user);
        $cartService = app(CartService::class);
        $cartService->add($product, 2);
        $cart = $cartService->current();

        $order = app(CreateOrderFromCart::class)->handle($cart, $user, $address);

        $this->assertSame(OrderStatus::Pending, $order->status);
        $this->assertSame(2000, $order->subtotal);
        $this->assertSame(2000, $order->total);
        $this->assertCount(1, $order->items);
        $this->assertSame(3, $product->fresh()->stock);
        $this->assertSame(0, $cart->items()->count());
    }

    public function test_it_throws_when_stock_is_insufficient(): void
    {
        $user = User::factory()->create();
        $address = Address::factory()->for($user)->create();
        $product = Product::factory()->create(['stock' => 1]);

        $this->actingAs($user);
        $cartService = app(CartService::class);
        $cartService->add($product, 1);
        $cart = $cartService->current();

        // Simulate stock being sold out by another order between add-to-cart and checkout.
        $product->update(['stock' => 0]);

        $this->expectException(OutOfStockException::class);

        app(CreateOrderFromCart::class)->handle($cart, $user, $address);
    }
}
