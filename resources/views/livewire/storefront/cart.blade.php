<div>
    <h1 class="mb-6 text-xl font-semibold">Your cart</h1>

    @error('cart')
        <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900 dark:bg-red-950 dark:text-red-400">
            {{ $message }}
        </div>
    @enderror

    @if ($cart->items->isEmpty())
        <div class="rounded-lg border border-dashed border-gray-300 py-16 text-center dark:border-gray-700">
            <p class="text-gray-500 dark:text-gray-400">Your cart is empty.</p>
            <a href="{{ route('products.index') }}" wire:navigate class="mt-4 inline-block text-sm font-medium text-indigo-600 hover:underline dark:text-indigo-400">
                Browse products &rarr;
            </a>
        </div>
    @else
        <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-800">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500 dark:bg-gray-900 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Product</th>
                        <th class="px-4 py-3">Price</th>
                        <th class="px-4 py-3">Quantity</th>
                        <th class="px-4 py-3">Subtotal</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @foreach ($cart->items as $item)
                        <tr wire:key="cart-item-{{ $item->id }}">
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-12 w-12 shrink-0 overflow-hidden rounded bg-gray-100 dark:bg-gray-800">
                                        @if ($item->product->images->first())
                                            <img src="{{ $item->product->images->first()->url }}" class="h-full w-full object-cover">
                                        @endif
                                    </div>
                                    <a href="{{ route('products.show', $item->product) }}" wire:navigate class="font-medium hover:text-indigo-600 dark:hover:text-indigo-400">
                                        {{ $item->product->name }}
                                    </a>
                                </div>
                            </td>
                            <td class="px-4 py-4">${{ number_format($item->unit_price / 100, 2) }}</td>
                            <td class="px-4 py-4">
                                <input
                                    type="number"
                                    min="1"
                                    max="{{ $item->product->stock }}"
                                    value="{{ $item->quantity }}"
                                    wire:change="updateQuantity({{ $item->id }}, $event.target.value)"
                                    class="w-20 rounded-md border border-gray-300 px-2 py-1 text-sm dark:border-gray-700 dark:bg-gray-800"
                                >
                            </td>
                            <td class="px-4 py-4 font-medium">${{ number_format($item->subtotal() / 100, 2) }}</td>
                            <td class="px-4 py-4 text-right">
                                <button wire:click="remove({{ $item->id }})" class="text-sm text-red-600 hover:underline dark:text-red-400">
                                    Remove
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-end">
            <div class="w-full max-w-xs space-y-2 text-sm">
                <div class="flex justify-between font-semibold text-base">
                    <span>Subtotal</span>
                    <span>${{ number_format($cart->subtotal() / 100, 2) }}</span>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Shipping and taxes calculated at checkout.</p>

                <a
                    href="{{ route('checkout.show') }}"
                    wire:navigate
                    class="mt-4 block w-full rounded-md bg-indigo-600 px-4 py-2.5 text-center text-sm font-medium text-white hover:bg-indigo-500"
                >
                    Proceed to checkout
                </a>
            </div>
        </div>
    @endif
</div>
