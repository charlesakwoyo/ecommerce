<div>
    <div class="mb-6 flex items-center justify-between gap-4">
        <h1 class="text-xl font-semibold">Orders</h1>

        <select wire:model.live="status" class="rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800">
            <option value="">All statuses</option>
            @foreach ($statuses as $case)
                <option value="{{ $case->value }}">{{ $case->label() }}</option>
            @endforeach
        </select>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-800">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500 dark:bg-gray-900 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Customer</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                @forelse ($orders as $order)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $order->order_number }}</td>
                        <td class="px-4 py-3">{{ $order->user?->name ?? 'Guest' }}</td>
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $order->created_at->format('M j, Y') }}</td>
                        <td class="px-4 py-3"><x-order-status-badge :status="$order->status" /></td>
                        <td class="px-4 py-3"><x-money :amount="$order->total" /></td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.orders.show', $order) }}" wire:navigate class="text-brand-600 hover:underline dark:text-brand-400">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td class="px-4 py-6 text-center text-gray-500" colspan="6">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
</div>
