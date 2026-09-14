<?php

namespace Tests\Feature;

use App\Livewire\Storefront\Cart as CartComponent;
use App\Livewire\Storefront\ProductShow;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_add_a_product_to_the_cart(): void
    {
        $product = Product::factory()->create(['stock' => 5]);

        Livewire::test(ProductShow::class, ['product' => $product])
            ->set('quantity', 2)
            ->call('addToCart')
            ->assertHasNoErrors();

        $cart = app(CartService::class)->current();

        $this->assertSame(2, $cart->items()->sole()->quantity);
    }

    public function test_adding_more_than_available_stock_fails(): void
    {
        $product = Product::factory()->create(['stock' => 2]);

        Livewire::test(ProductShow::class, ['product' => $product])
            ->set('quantity', 5)
            ->call('addToCart')
            ->assertHasErrors('quantity');

        $this->assertSame(0, CartItem::query()->count());
    }

    public function test_cart_quantity_can_be_updated(): void
    {
        $product = Product::factory()->create(['stock' => 10]);
        $cart = app(CartService::class)->current();
        $item = app(CartService::class)->add($product, 1);

        Livewire::test(CartComponent::class)
            ->call('updateQuantity', $item->id, 3)
            ->assertHasNoErrors();

        $this->assertSame(3, $item->fresh()->quantity);
    }

    public function test_item_can_be_removed_from_cart(): void
    {
        $product = Product::factory()->create(['stock' => 10]);
        $item = app(CartService::class)->add($product, 1);

        Livewire::test(CartComponent::class)
            ->call('remove', $item->id);

        $this->assertSame(0, CartItem::query()->count());
    }

    public function test_guest_cart_merges_into_user_cart_on_login(): void
    {
        $product = Product::factory()->create(['stock' => 10]);
        $user = User::factory()->create();

        app(CartService::class)->add($product, 2);

        $this->actingAs($user);
        app(CartService::class)->mergeGuestCartIntoUser($user);

        $this->assertSame(2, $user->cart->items()->sole()->quantity);
    }
}
