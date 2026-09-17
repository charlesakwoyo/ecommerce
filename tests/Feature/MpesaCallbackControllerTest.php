<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MpesaCallbackControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_callback_marks_the_order_as_paid(): void
    {
        $product = Product::factory()->create(['stock' => 4]);
        $order = Order::factory()->create([
            'status' => OrderStatus::Pending,
            'payment_method' => 'mpesa',
            'mpesa_checkout_request_id' => 'ws_CO_1',
        ]);
        $order->items()->create([
            'product_id' => $product->id,
            'name' => $product->name,
            'unit_price' => $product->price,
            'quantity' => 1,
        ]);

        $response = $this->postJson(route('mpesa.callback'), [
            'Body' => [
                'stkCallback' => [
                    'MerchantRequestID' => 'merchant-1',
                    'CheckoutRequestID' => 'ws_CO_1',
                    'ResultCode' => 0,
                    'ResultDesc' => 'The service request is processed successfully.',
                    'CallbackMetadata' => [
                        'Item' => [
                            ['Name' => 'Amount', 'Value' => 1500],
                            ['Name' => 'MpesaReceiptNumber', 'Value' => 'NLJ7RT61SV'],
                            ['Name' => 'TransactionDate', 'Value' => 20260915102115],
                            ['Name' => 'PhoneNumber', 'Value' => 254712345678],
                        ],
                    ],
                ],
            ],
        ]);

        $response->assertOk();

        $order->refresh();
        $this->assertSame(OrderStatus::Paid, $order->status);
        $this->assertSame('NLJ7RT61SV', $order->mpesa_receipt_number);
        $this->assertNotNull($order->paid_at);
    }

    public function test_failed_callback_cancels_the_order_and_releases_stock(): void
    {
        $product = Product::factory()->create(['stock' => 4]);
        $order = Order::factory()->create([
            'status' => OrderStatus::Pending,
            'payment_method' => 'mpesa',
            'mpesa_checkout_request_id' => 'ws_CO_2',
        ]);
        $order->items()->create([
            'product_id' => $product->id,
            'name' => $product->name,
            'unit_price' => $product->price,
            'quantity' => 2,
        ]);

        $response = $this->postJson(route('mpesa.callback'), [
            'Body' => [
                'stkCallback' => [
                    'MerchantRequestID' => 'merchant-2',
                    'CheckoutRequestID' => 'ws_CO_2',
                    'ResultCode' => 1032,
                    'ResultDesc' => 'Request cancelled by user.',
                ],
            ],
        ]);

        $response->assertOk();

        $order->refresh();
        $this->assertSame(OrderStatus::Cancelled, $order->status);
        $this->assertSame(6, $product->fresh()->stock);
    }

    public function test_callback_for_an_unknown_checkout_request_id_is_ignored_gracefully(): void
    {
        $response = $this->postJson(route('mpesa.callback'), [
            'Body' => [
                'stkCallback' => [
                    'MerchantRequestID' => 'merchant-3',
                    'CheckoutRequestID' => 'does-not-exist',
                    'ResultCode' => 0,
                ],
            ],
        ]);

        $response->assertOk();
    }
}
