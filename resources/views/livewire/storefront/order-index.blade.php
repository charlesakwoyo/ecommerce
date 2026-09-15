<div>
    <h1 class="mb-6 text-xl font-semibold">My orders</h1>

    @if ($orders->isEmpty())
        <div class="rounded-lg border border-dashed border-gray-300 py-16 text-center dark:border-gray-700">
            <p class="text-gray-500 dark:text-gray-400">You haven't placed any orders yet.</p>
            <a href="{{ route('products.index') }}" wire:navigate class="mt-4 inline-block text-sm font-medium text-brand-600 hover:underline dark:text-brand-400">
                Browse products &rarr;
            </a>
        </div>
    @else
        <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-800">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500 dark:bg-gray-900 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Order</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Total</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @foreach ($orders as $order)
                        <tr>
                            <td class="px-4 py-4 font-medium">{{ $order->order_number }}</td>
                            <td class="px-4 py-4">{{ $order->created_at->format('M j, Y') }}</td>
                            <td class="px-4 py-4">
                                <x-order-status-badge :status="$order->status" />
                            </td>
                            <td class="px-4 py-4"><x-money :amount="$order->total" /></td>
                            <td class="px-4 py-4 text-right">
                                <a href="{{ route('orders.show', $order) }}" wire:navigate class="text-brand-600 hover:underline dark:text-brand-400">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</div>
