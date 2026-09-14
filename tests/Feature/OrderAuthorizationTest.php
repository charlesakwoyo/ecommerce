<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_view_another_users_order(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $order = Order::factory()->for($owner)->create();

        $response = $this->actingAs($other)->get(route('orders.show', $order));

        $response->assertForbidden();
    }

    public function test_user_can_view_their_own_order(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->for($user)->create();

        $response = $this->actingAs($user)->get(route('orders.show', $order));

        $response->assertOk();
    }

    public function test_admin_can_view_any_order(): void
    {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->create();
        $order = Order::factory()->for($owner)->create();

        $response = $this->actingAs($admin)->get(route('orders.show', $order));

        $response->assertOk();
    }
}
