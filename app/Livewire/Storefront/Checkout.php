<?php

namespace App\Livewire\Storefront;

use App\Actions\Orders\CancelOrderAndReleaseStock;
use App\Actions\Orders\CreateOrderFromCart;
use App\Enums\AddressType;
use App\Enums\OrderStatus;
use App\Exceptions\MpesaRequestException;
use App\Exceptions\OutOfStockException;
use App\Models\Address;
use App\Models\Order;
use App\Services\CartService;
use App\Services\MpesaService;
use App\Services\PricingCalculator;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Checkout')]
class Checkout extends Component
{
    public ?int $selectedAddressId = null;

    public bool $useNewAddress = false;

    public string $name = '';

    public string $line1 = '';

    public string $line2 = '';

    public string $city = '';

    public string $state = '';

    public string $postal_code = '';

    public string $country = '';

    public string $phone = '';

    public string $paymentMethod = 'card';

    public string $mpesaPhone = '';

    public ?int $awaitingOrderId = null;

    public string $mpesaMessage = '';

    public function mount(): void
    {
        $default = auth()->user()->addresses()->where('is_default', true)->first()
            ?? auth()->user()->addresses()->first();

        if ($default) {
            $this->selectedAddressId = $default->id;
            $this->mpesaPhone = (string) $default->phone;
        } else {
            $this->useNewAddress = true;
        }
    }

    public function placeOrder(CartService $cartService, CreateOrderFromCart $createOrder): void
    {
        $cart = $cartService->current();
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            $this->addError('cart', 'Your cart is empty.');

            return;
        }

        $address = $this->useNewAddress
            ? $this->createAddressFromInput()
            : auth()->user()->addresses()->findOrFail($this->selectedAddressId);

        if ($this->paymentMethod === 'mpesa') {
            $this->validate(['mpesaPhone' => ['required', 'string', 'min:9', 'max:15']]);
        }

        try {
            $order = $createOrder->handle($cart, auth()->user(), $address);
        } catch (OutOfStockException $exception) {
            $this->addError('cart', $exception->getMessage());

            return;
        }

        if ($this->paymentMethod === 'mpesa') {
            $this->startMpesaPayment($order);

            return;
        }

        $this->startStripeCheckout($order);
    }

    private function startStripeCheckout(Order $order): void
    {
        $checkout = auth()->user()->checkout($order->stripeLineItems(), [
            'success_url' => route('orders.show', $order).'?checkout=success',
            'cancel_url' => route('checkout.show').'?checkout=cancelled',
            'metadata' => ['order_id' => $order->id],
        ]);

        $order->update(['payment_method' => 'card', 'stripe_checkout_session_id' => $checkout->id]);

        $this->redirect($checkout->url);
    }

    private function startMpesaPayment(Order $order): void
    {
        try {
            $result = app(MpesaService::class)->stkPush($order, $this->mpesaPhone);
        } catch (MpesaRequestException $exception) {
            app(CancelOrderAndReleaseStock::class)->handle($order);
            $this->addError('mpesa', $exception->getMessage());

            return;
        }

        $this->awaitingOrderId = $order->id;
        $this->mpesaMessage = $result['customer_message'];
    }

    /**
     * Polled from the "waiting for M-Pesa" screen to actively check the
     * transaction result, in case the asynchronous callback hasn't arrived.
     */
    public function pollMpesaStatus(MpesaService $mpesaService): void
    {
        if (! $this->awaitingOrderId) {
            return;
        }

        $order = Order::query()->find($this->awaitingOrderId);

        if (! $order || $order->status === OrderStatus::Paid) {
            $this->redirect(route('orders.show', $this->awaitingOrderId).'?checkout=success');

            return;
        }

        if ($order->status === OrderStatus::Cancelled) {
            $this->awaitingOrderId = null;
            $this->addError('mpesa', 'Payment was not completed. Please try again.');

            return;
        }

        if (! $order->mpesa_checkout_request_id) {
            return;
        }

        $status = $mpesaService->queryStatus($order->mpesa_checkout_request_id);

        if (! $status['completed']) {
            return;
        }

        if ($status['success']) {
            $this->redirect(route('orders.show', $order).'?checkout=success');

            return;
        }

        app(CancelOrderAndReleaseStock::class)->handle($order);
        $this->awaitingOrderId = null;
        $this->addError('mpesa', $status['description'] ?: 'Payment was not completed. Please try again.');
    }

    public function cancelMpesaWait(): void
    {
        if ($this->awaitingOrderId && $order = Order::query()->find($this->awaitingOrderId)) {
            app(CancelOrderAndReleaseStock::class)->handle($order);
        }

        $this->awaitingOrderId = null;
    }

    private function createAddressFromInput(): Address
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'line1' => ['required', 'string', 'max:255'],
            'line2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'size:2'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        return auth()->user()->addresses()->create([
            'type' => AddressType::Shipping,
            'name' => $this->name,
            'line1' => $this->line1,
            'line2' => $this->line2,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'country' => strtoupper($this->country),
            'phone' => $this->phone,
            'is_default' => auth()->user()->addresses()->doesntExist(),
        ]);
    }

    public function render(): View
    {
        $cart = app(CartService::class)->current();
        $cart->load('items.product');

        $country = $this->useNewAddress
            ? $this->country
            : auth()->user()->addresses()->find($this->selectedAddressId)?->country;

        $pricing = $country
            ? app(PricingCalculator::class)->calculate($cart->subtotal(), $country)
            : ['tax' => 0, 'shipping' => 0];

        return view('livewire.storefront.checkout', [
            'cart' => $cart,
            'savedAddresses' => auth()->user()->addresses,
            'awaitingOrder' => $this->awaitingOrderId ? Order::query()->find($this->awaitingOrderId) : null,
            'pricing' => $pricing,
        ]);
    }
}
