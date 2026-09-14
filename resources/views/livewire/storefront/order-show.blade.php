<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-semibold">Order {{ $order->order_number }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Placed {{ $order->created_at->format('M j, Y \a\t g:ia') }}</p>
        </div>
        <x-order-status-badge :status="$order->status" />
    </div>

    @if (request()->query('checkout') === 'success')
        <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-900 dark:bg-green-950 dark:text-green-300">
            Thanks for your order! We'll email you a confirmation shortly.
        </div>
    @endif

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-800">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500 dark:bg-gray-900 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">Product</th>
                            <th class="px-4 py-3">Price</th>
                            <th class="px-4 py-3">Qty</th>
                            <th class="px-4 py-3">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="px-4 py-4">
                                    @if ($item->product)
                                        <a href="{{ route('products.show', $item->product) }}" wire:navigate class="font-medium hover:text-indigo-600 dark:hover:text-indigo-400">
                                            {{ $item->name }}
                                        </a>
                                    @else
                                        {{ $item->name }}
                                    @endif
                                </td>
                                <td class="px-4 py-4">${{ number_format($item->unit_price / 100, 2) }}</td>
                                <td class="px-4 py-4">{{ $item->quantity }}</td>
                                <td class="px-4 py-4 font-medium">${{ number_format($item->subtotal() / 100, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-6">
            <div>
                <h2 class="mb-2 text-sm font-semibold uppercase text-gray-500 dark:text-gray-400">Summary</h2>
                <div class="space-y-1 rounded-lg border border-gray-200 p-4 text-sm dark:border-gray-800">
                    <div class="flex justify-between"><span>Subtotal</span><span>${{ number_format($order->subtotal / 100, 2) }}</span></div>
                    <div class="flex justify-between"><span>Shipping</span><span>${{ number_format($order->shipping / 100, 2) }}</span></div>
                    <div class="flex justify-between"><span>Tax</span><span>${{ number_format($order->tax / 100, 2) }}</span></div>
                    <div class="flex justify-between border-t border-gray-200 pt-2 font-semibold dark:border-gray-800">
                        <span>Total</span><span>${{ number_format($order->total / 100, 2) }}</span>
                    </div>
                </div>
            </div>

            @if ($order->shipping_address)
                <div>
                    <h2 class="mb-2 text-sm font-semibold uppercase text-gray-500 dark:text-gray-400">Shipping address</h2>
                    <div class="rounded-lg border border-gray-200 p-4 text-sm dark:border-gray-800">
                        <p class="font-medium">{{ $order->shipping_address['name'] }}</p>
                        <p class="text-gray-500 dark:text-gray-400">
                            {{ $order->shipping_address['line1'] }}
                            @if (! empty($order->shipping_address['line2']))
                                , {{ $order->shipping_address['line2'] }}
                            @endif
                            <br>
                            {{ $order->shipping_address['city'] }}, {{ $order->shipping_address['state'] }} {{ $order->shipping_address['postal_code'] }}
                            <br>
                            {{ $order->shipping_address['country'] }}
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
