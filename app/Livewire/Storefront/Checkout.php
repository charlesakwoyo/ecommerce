<?php

namespace App\Livewire\Storefront;

use App\Actions\Orders\CreateOrderFromCart;
use App\Enums\AddressType;
use App\Exceptions\OutOfStockException;
use App\Models\Address;
use App\Services\CartService;
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

    public function mount(): void
    {
        $default = auth()->user()->addresses()->where('is_default', true)->first()
            ?? auth()->user()->addresses()->first();

        if ($default) {
            $this->selectedAddressId = $default->id;
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

        try {
            $order = $createOrder->handle($cart, auth()->user(), $address);
        } catch (OutOfStockException $exception) {
            $this->addError('cart', $exception->getMessage());

            return;
        }

        $lineItems = $order->items->map(fn ($item) => [
            'price_data' => [
                'currency' => $order->currency,
                'product_data' => ['name' => $item->name],
                'unit_amount' => $item->unit_price,
            ],
            'quantity' => $item->quantity,
        ])->all();

        $checkout = auth()->user()->checkout($lineItems, [
            'success_url' => route('orders.show', $order).'?checkout=success',
            'cancel_url' => route('checkout.show').'?checkout=cancelled',
            'metadata' => ['order_id' => $order->id],
        ]);

        $order->update(['stripe_checkout_session_id' => $checkout->id]);

        $this->redirect($checkout->url);
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

        return view('livewire.storefront.checkout', [
            'cart' => $cart,
            'savedAddresses' => auth()->user()->addresses,
        ]);
    }
}
