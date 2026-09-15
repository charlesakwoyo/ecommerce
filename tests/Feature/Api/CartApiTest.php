<?php

namespace Tests\Feature\Api;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_the_cart(): void
    {
        $this->getJson('/api/v1/cart')->assertUnauthorized();
    }

    public function test_authenticated_user_can_add_a_product_to_their_cart(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 5]);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/cart/items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertOk()->assertJsonPath('data.total_quantity', 2);
        $this->assertSame(2, CartItem::query()->sole()->quantity);
    }

    public function test_adding_more_than_available_stock_fails(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 2]);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/cart/items', [
            'product_id' => $product->id,
            'quantity' => 5,
        ]);

        $response->assertUnprocessable();
        $this->assertSame(0, CartItem::query()->count());
    }

    public function test_user_can_update_the_quantity_of_their_own_cart_item(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);
        $this->actingAs($user);
        $item = app(CartService::class)->add($product, 1);

        $response = $this->actingAs($user, 'sanctum')->patchJson("/api/v1/cart/items/{$item->id}", [
            'quantity' => 4,
        ]);

        $response->assertOk()->assertJsonPath('data.total_quantity', 4);
    }

    public function test_user_cannot_update_another_users_cart_item(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);
        $this->actingAs($owner);
        $item = app(CartService::class)->add($product, 1);

        $response = $this->actingAs($other, 'sanctum')->patchJson("/api/v1/cart/items/{$item->id}", [
            'quantity' => 2,
        ]);

        $response->assertForbidden();
    }

    public function test_user_can_remove_an_item_from_their_cart(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);
        $this->actingAs($user);
        $item = app(CartService::class)->add($product, 1);

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/v1/cart/items/{$item->id}");

        $response->assertOk()->assertJsonPath('data.total_quantity', 0);
        $this->assertSame(0, CartItem::query()->count());
    }
}
