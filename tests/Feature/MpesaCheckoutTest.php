<?php

namespace Tests\Feature;

use App\Livewire\Storefront\Checkout;
use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class MpesaCheckoutTest extends TestCase
{
    use RefreshDatabase;

    private function fakeOAuth(): void
    {
        Http::fake([
            '*/oauth/v1/generate*' => Http::response(['access_token' => 'fake-token', 'expires_in' => '3599']),
        ]);
    }

    public function test_placing_an_order_with_mpesa_sends_an_stk_push_and_awaits_confirmation(): void
    {
        $this->fakeOAuth();
        Http::fake([
            '*/mpesa/stkpush/v1/processrequest' => Http::response([
                'MerchantRequestID' => 'merchant-1',
                'CheckoutRequestID' => 'ws_CO_1',
                'ResponseCode' => '0',
                'ResponseDescription' => 'Success. Request accepted for processing',
                'CustomerMessage' => 'Success. Request accepted for processing',
            ]),
            '*/oauth/v1/generate*' => Http::response(['access_token' => 'fake-token', 'expires_in' => '3599']),
        ]);

        $user = User::factory()->create();
        $address = Address::factory()->for($user)->create();
        $product = Product::factory()->create(['stock' => 5]);

        $this->actingAs($user);
        app(CartService::class)->add($product, 1);

        Livewire::test(Checkout::class)
            ->set('selectedAddressId', $address->id)
            ->set('paymentMethod', 'mpesa')
            ->set('mpesaPhone', '0712345678')
            ->call('placeOrder')
            ->assertHasNoErrors();

        $order = Order::query()->sole();

        $this->assertSame('mpesa', $order->payment_method);
        $this->assertSame('ws_CO_1', $order->mpesa_checkout_request_id);
        $this->assertSame('254712345678', $order->mpesa_phone);
        $this->assertSame(4, $product->fresh()->stock);
    }

    public function test_stk_push_rejection_cancels_the_order_and_releases_stock(): void
    {
        $this->fakeOAuth();
        Http::fake([
            '*/mpesa/stkpush/v1/processrequest' => Http::response([
                'ResponseCode' => '1',
                'ResponseDescription' => 'Invalid PhoneNumber',
                'errorMessage' => 'Invalid PhoneNumber',
            ], 400),
            '*/oauth/v1/generate*' => Http::response(['access_token' => 'fake-token', 'expires_in' => '3599']),
        ]);

        $user = User::factory()->create();
        $address = Address::factory()->for($user)->create();
        $product = Product::factory()->create(['stock' => 5]);

        $this->actingAs($user);
        app(CartService::class)->add($product, 1);

        Livewire::test(Checkout::class)
            ->set('selectedAddressId', $address->id)
            ->set('paymentMethod', 'mpesa')
            ->set('mpesaPhone', '0712345678')
            ->call('placeOrder')
            ->assertHasErrors('mpesa');

        $this->assertSame(5, $product->fresh()->stock);
        $this->assertSame('cancelled', Order::query()->sole()->status->value);
    }

    public function test_mpesa_phone_is_required(): void
    {
        $user = User::factory()->create();
        $address = Address::factory()->for($user)->create();
        $product = Product::factory()->create(['stock' => 5]);

        $this->actingAs($user);
        app(CartService::class)->add($product, 1);

        Livewire::test(Checkout::class)
            ->set('selectedAddressId', $address->id)
            ->set('paymentMethod', 'mpesa')
            ->set('mpesaPhone', '')
            ->call('placeOrder')
            ->assertHasErrors('mpesaPhone');

        $this->assertSame(0, Order::query()->count());
    }
}
