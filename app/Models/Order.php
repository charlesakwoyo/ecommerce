<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'user_id', 'order_number', 'status', 'subtotal', 'tax', 'shipping', 'total',
    'currency', 'payment_method', 'shipping_address', 'billing_address',
    'stripe_checkout_session_id', 'stripe_payment_intent_id',
    'mpesa_checkout_request_id', 'mpesa_merchant_request_id', 'mpesa_receipt_number', 'mpesa_phone',
    'paid_at',
])]
class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'subtotal' => 'integer',
            'tax' => 'integer',
            'shipping' => 'integer',
            'total' => 'integer',
            'shipping_address' => 'array',
            'billing_address' => 'array',
            'paid_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Build Stripe Checkout line items for this order's products, plus
     * shipping and tax as their own line items so the amount Stripe charges
     * matches the order total.
     *
     * @return array<int, array<string, mixed>>
     */
    public function stripeLineItems(): array
    {
        $lineItems = $this->items->map(fn (OrderItem $item) => [
            'price_data' => [
                'currency' => $this->currency,
                'product_data' => ['name' => $item->name],
                'unit_amount' => $item->unit_price,
            ],
            'quantity' => $item->quantity,
        ])->all();

        if ($this->shipping > 0) {
            $lineItems[] = $this->feeLineItem('Shipping', $this->shipping);
        }

        if ($this->tax > 0) {
            $lineItems[] = $this->feeLineItem('Tax (VAT)', $this->tax);
        }

        return $lineItems;
    }

    /**
     * @return array<string, mixed>
     */
    private function feeLineItem(string $name, int $amount): array
    {
        return [
            'price_data' => [
                'currency' => $this->currency,
                'product_data' => ['name' => $name],
                'unit_amount' => $amount,
            ],
            'quantity' => 1,
        ];
    }

    #[Scope]
    protected function forUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }
}
