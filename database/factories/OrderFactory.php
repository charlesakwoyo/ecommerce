<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->numberBetween(1000, 20000);
        $shipping = fake()->numberBetween(0, 1500);

        return [
            'user_id' => User::factory(),
            'order_number' => 'ORD-'.strtoupper(Str::random(10)),
            'status' => OrderStatus::Pending,
            'subtotal' => $subtotal,
            'tax' => 0,
            'shipping' => $shipping,
            'total' => $subtotal + $shipping,
            'currency' => 'usd',
            'shipping_address' => [
                'name' => fake()->name(),
                'line1' => fake()->streetAddress(),
                'line2' => null,
                'city' => fake()->city(),
                'state' => fake()->state(),
                'postal_code' => fake()->postcode(),
                'country' => fake()->countryCode(),
                'phone' => fake()->phoneNumber(),
            ],
            'billing_address' => null,
        ];
    }

    /**
     * Indicate that the order has been paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderStatus::Paid,
            'paid_at' => now(),
        ]);
    }
}
