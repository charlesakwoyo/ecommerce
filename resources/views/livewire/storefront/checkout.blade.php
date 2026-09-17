<div>
    @if ($awaitingOrder)
        <div wire:poll.3s="pollMpesaStatus" class="mx-auto max-w-md rounded-lg border border-gray-200 p-8 text-center dark:border-gray-800">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-green-100 dark:bg-green-950">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-7 w-7 animate-pulse text-green-600 dark:text-green-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                </svg>
            </div>

            <h2 class="mt-4 text-lg font-semibold text-gray-900 dark:text-gray-100">Check your phone</h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                {{ $mpesaMessage ?: 'We sent an M-Pesa payment prompt to '.$awaitingOrder->mpesa_phone.'. Enter your M-Pesa PIN to complete the payment.' }}
            </p>

            @error('mpesa')
                <p class="mt-3 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror

            <p class="mt-4 text-xs text-gray-400">Waiting for confirmation&hellip; this page will update automatically.</p>

            <button wire:click="cancelMpesaWait" class="mt-6 text-sm font-medium text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                Cancel and choose another payment method
            </button>
        </div>
    @else
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <h1 class="mb-6 text-xl font-semibold">Checkout</h1>

                @error('cart')
                    <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900 dark:bg-red-950 dark:text-red-400">
                        {{ $message }}
                    </div>
                @enderror

                @error('mpesa')
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
                        <x-input name="country" label="Country code (e.g. KE)" wire:model.live="country" maxlength="2" :error="$errors->first('country')" />
                        <div class="sm:col-span-2">
                            <x-input name="phone" label="Phone (optional)" wire:model="phone" :error="$errors->first('phone')" />
                        </div>
                    </div>
                @endif

                <h2 class="mt-8 mb-3 text-sm font-semibold uppercase text-gray-500 dark:text-gray-400">Payment method</h2>

                <div class="space-y-2">
                    <label class="flex cursor-pointer items-center gap-3 rounded-md border border-gray-200 p-3 text-sm dark:border-gray-800 {{ $paymentMethod === 'card' ? 'border-brand-500 ring-1 ring-brand-500' : '' }}">
                        <input type="radio" name="paymentMethod" wire:model.live="paymentMethod" value="card" @checked($paymentMethod === 'card')>
                        <span class="font-medium">Pay by card</span>
                        <span class="ml-auto text-xs text-gray-400">Visa &middot; Mastercard &middot; Amex</span>
                    </label>

                    <label class="flex cursor-pointer items-center gap-3 rounded-md border border-gray-200 p-3 text-sm dark:border-gray-800 {{ $paymentMethod === 'mpesa' ? 'border-brand-500 ring-1 ring-brand-500' : '' }}">
                        <input type="radio" name="paymentMethod" wire:model.live="paymentMethod" value="mpesa" @checked($paymentMethod === 'mpesa')>
                        <span class="font-medium">Pay with M-Pesa</span>
                        <span class="ml-auto text-xs font-semibold text-green-600 dark:text-green-400">STK Push</span>
                    </label>

                    @if ($paymentMethod === 'mpesa')
                        <div class="rounded-md border border-gray-200 p-4 dark:border-gray-800">
                            <x-input
                                name="mpesaPhone"
                                label="M-Pesa phone number"
                                placeholder="07XXXXXXXX"
                                wire:model="mpesaPhone"
                                :error="$errors->first('mpesaPhone')"
                            />
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                You'll receive a prompt on this phone to enter your M-Pesa PIN and confirm payment.
                            </p>
                        </div>
                    @endif
                </div>
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

                    <div class="border-t border-gray-200 pt-3 dark:border-gray-800">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span><x-money :amount="$cart->subtotal()" /></span>
                        </div>
                        <div class="mt-1 flex justify-between">
                            <span>Shipping</span>
                            <span><x-money :amount="$pricing['shipping']" /></span>
                        </div>
                        <div class="mt-1 flex justify-between">
                            <span>Tax (VAT)</span>
                            <span><x-money :amount="$pricing['tax']" /></span>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-3 font-semibold dark:border-gray-800">
                        <div class="flex justify-between">
                            <span>Total</span>
                            <span><x-money :amount="$cart->subtotal() + $pricing['shipping'] + $pricing['tax']" /></span>
                        </div>
                    </div>
                </div>

                <button
                    wire:click="placeOrder"
                    wire:loading.attr="disabled"
                    wire:target="placeOrder"
                    class="mt-4 w-full rounded-md bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 disabled:opacity-60"
                >
                    <span wire:loading.remove wire:target="placeOrder">
                        {{ $paymentMethod === 'mpesa' ? 'Pay with M-Pesa' : 'Continue to payment' }}
                    </span>
                    <span wire:loading wire:target="placeOrder">
                        {{ $paymentMethod === 'mpesa' ? 'Sending M-Pesa prompt…' : 'Redirecting to Stripe…' }}
                    </span>
                </button>
            </div>
        </div>
    @endif
</div>
