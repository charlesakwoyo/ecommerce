<?php

namespace Tests\Feature\Api;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_list_orders(): void
    {
        $this->getJson('/api/v1/orders')->assertUnauthorized();
    }

    public function test_user_only_sees_their_own_orders_in_the_list(): void
    {
        $user = User::factory()->create();
        $theirOrder = Order::factory()->for($user)->create();
        Order::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/orders');

        $response->assertOk();
        $ids = collect($response->json('data'))->pluck('id');

        $this->assertTrue($ids->contains($theirOrder->id));
        $this->assertCount(1, $ids);
    }

    public function test_user_can_view_their_own_order(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->for($user)->create();

        $response = $this->actingAs($user, 'sanctum')->getJson("/api/v1/orders/{$order->id}");

        $response->assertOk()->assertJsonPath('data.id', $order->id);
    }

    public function test_user_cannot_view_another_users_order(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $order = Order::factory()->for($owner)->create();

        $response = $this->actingAs($other, 'sanctum')->getJson("/api/v1/orders/{$order->id}");

        $response->assertForbidden();
    }
}
