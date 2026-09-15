<?php

namespace Tests\Feature\Api;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_checkout(): void
    {
        $this->postJson('/api/v1/checkout')->assertUnauthorized();
    }

    public function test_checkout_fails_when_the_cart_is_empty(): void
    {
        $user = User::factory()->create();
        $address = Address::factory()->for($user)->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/checkout', [
            'address_id' => $address->id,
            'success_url' => 'https://example.com/success',
            'cancel_url' => 'https://example.com/cancel',
        ]);

        $response->assertUnprocessable()->assertJsonPath('message', 'Your cart is empty.');
    }

    public function test_checkout_requires_either_an_address_id_or_inline_address(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/checkout', [
            'success_url' => 'https://example.com/success',
            'cancel_url' => 'https://example.com/cancel',
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors(['address_id', 'address']);
    }
}
