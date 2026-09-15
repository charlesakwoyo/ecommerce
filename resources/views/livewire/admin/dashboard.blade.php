<div>
    <h1 class="mb-6 text-xl font-semibold">Dashboard</h1>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">Products</p>
            <p class="mt-1 text-2xl font-semibold">{{ $productCount }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">Low stock (&le;5)</p>
            <p class="mt-1 text-2xl font-semibold">{{ $lowStockCount }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">Pending orders</p>
            <p class="mt-1 text-2xl font-semibold">{{ $pendingOrderCount }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">Revenue (paid)</p>
            <p class="mt-1 text-2xl font-semibold"><x-money :amount="$revenue" /></p>
        </div>
    </div>

    <div class="mt-8">
        <h2 class="mb-4 text-sm font-semibold uppercase text-gray-500 dark:text-gray-400">Recent orders</h2>

        <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-800">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500 dark:bg-gray-900 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Order</th>
                        <th class="px-4 py-3">Customer</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse ($recentOrders as $order)
                        <tr>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.orders.show', $order) }}" wire:navigate class="font-medium text-brand-600 hover:underline dark:text-brand-400">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td class="px-4 py-3">{{ $order->user?->name ?? 'Guest' }}</td>
                            <td class="px-4 py-3"><x-order-status-badge :status="$order->status" /></td>
                            <td class="px-4 py-3"><x-money :amount="$order->total" /></td>
                        </tr>
                    @empty
                        <tr><td class="px-4 py-6 text-center text-gray-500" colspan="4">No orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
