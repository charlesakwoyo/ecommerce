<div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
    <div class="lg:col-span-2">
        <h1 class="mb-6 text-xl font-semibold">Checkout</h1>

        @error('cart')
            <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900 dark:bg-red-950 dark:text-red-400">
                {{ $message }}
            </div>
        @enderror

        <h2 class="mb-3 text-sm font-semibold uppercase text-gray-500 dark:text-gray-400">Shipping address</h2>

        @if ($savedAddresses->isNotEmpty())
            <div class="mb-4 space-y-2">
                @foreach ($savedAddresses as $address)
                    <label class="flex cursor-pointer items-start gap-3 rounded-md border border-gray-200 p-3 text-sm dark:border-gray-800 {{ ! $useNewAddress && $selectedAddressId === $address->id ? 'border-brand-500 ring-1 ring-brand-500' : '' }}">
                        <input
                            type="radio"
                            name="address"
                            wire:click="$set('useNewAddress', false); $set('selectedAddressId', {{ $address->id }})"
                            @checked(! $useNewAddress && $selectedAddressId === $address->id)
                            class="mt-1"
                        >
                        <span>
                            <span class="block font-medium">{{ $address->name }}</span>
                            <span class="block text-gray-500 dark:text-gray-400">
                                {{ $address->line1 }}{{ $address->line2 ? ', '.$address->line2 : '' }}, {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}, {{ $address->country }}
                            </span>
                        </span>
                    </label>
                @endforeach

                <label class="flex cursor-pointer items-center gap-3 rounded-md border border-gray-200 p-3 text-sm dark:border-gray-800 {{ $useNewAddress ? 'border-brand-500 ring-1 ring-brand-500' : '' }}">
                    <input type="radio" name="address" wire:click="$set('useNewAddress', true)" @checked($useNewAddress)>
                    <span class="font-medium">Use a new address</span>
                </label>
            </div>
        @endif

        @if ($useNewAddress)
            <div class="grid grid-cols-1 gap-4 rounded-lg border border-gray-200 p-4 dark:border-gray-800 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <x-input name="name" label="Full name" wire:model="name" :error="$errors->first('name')" />
                </div>
                <div class="sm:col-span-2">
                    <x-input name="line1" label="Address line 1" wire:model="line1" :error="$errors->first('line1')" />
                </div>
                <div class="sm:col-span-2">
                    <x-input name="line2" label="Address line 2 (optional)" wire:model="line2" :error="$errors->first('line2')" />
                </div>
                <x-input name="city" label="City" wire:model="city" :error="$errors->first('city')" />
                <x-input name="state" label="State / Province" wire:model="state" :error="$errors->first('state')" />
                <x-input name="postal_code" label="Postal code" wire:model="postal_code" :error="$errors->first('postal_code')" />
                <x-input name="country" label="Country code (e.g. US)" wire:model="country" maxlength="2" :error="$errors->first('country')" />
                <div class="sm:col-span-2">
                    <x-input name="phone" label="Phone (optional)" wire:model="phone" :error="$errors->first('phone')" />
                </div>
            </div>
        @endif
    </div>

    <div>
        <h2 class="mb-3 text-sm font-semibold uppercase text-gray-500 dark:text-gray-400">Order summary</h2>

        <div class="space-y-3 rounded-lg border border-gray-200 p-4 text-sm dark:border-gray-800">
            @foreach ($cart->items as $item)
                <div class="flex justify-between">
                    <span>{{ $item->product->name }} &times; {{ $item->quantity }}</span>
                    <span><x-money :amount="$item->subtotal()" /></span>
                </div>
            @endforeach

            <div class="border-t border-gray-200 pt-3 font-semibold dark:border-gray-800">
                <div class="flex justify-between">
                    <span>Total</span>
                    <span><x-money :amount="$cart->subtotal()" /></span>
                </div>
            </div>
        </div>

        <button
            wire:click="placeOrder"
            wire:loading.attr="disabled"
            wire:target="placeOrder"
            class="mt-4 w-full rounded-md bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 disabled:opacity-60"
        >
            <span wire:loading.remove wire:target="placeOrder">Continue to payment</span>
            <span wire:loading wire:target="placeOrder">Redirecting to Stripe&hellip;</span>
        </button>
    </div>
</div>
