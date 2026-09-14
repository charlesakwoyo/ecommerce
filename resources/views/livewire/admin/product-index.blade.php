<div>
    <div class="mb-6 flex items-center justify-between gap-4">
        <h1 class="text-xl font-semibold">Products</h1>
        <a href="{{ route('admin.products.create') }}" wire:navigate class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">
            New product
        </a>
    </div>

    <input
        type="search"
        wire:model.live.debounce.400ms="search"
        placeholder="Search products..."
        class="mb-4 w-full max-w-sm rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800"
    >

    <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-800">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500 dark:bg-gray-900 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Price</th>
                    <th class="px-4 py-3">Stock</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                @forelse ($products as $product)
                    <tr wire:key="admin-product-{{ $product->id }}">
                        <td class="px-4 py-3 font-medium">{{ $product->name }}</td>
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $product->category?->name ?? '—' }}</td>
                        <td class="px-4 py-3">${{ number_format($product->price / 100, 2) }}</td>
                        <td class="px-4 py-3 {{ $product->stock <= 5 ? 'text-red-600 dark:text-red-400' : '' }}">{{ $product->stock }}</td>
                        <td class="px-4 py-3">
                            <button
                                wire:click="toggleActive({{ $product->id }})"
                                class="rounded-full px-2.5 py-1 text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-800 dark:bg-green-950 dark:text-green-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' }}"
                            >
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.products.edit', $product) }}" wire:navigate class="mr-3 text-indigo-600 hover:underline dark:text-indigo-400">Edit</a>
                            <button
                                wire:click="delete({{ $product->id }})"
                                wire:confirm="Delete this product? This cannot be undone."
                                class="text-red-600 hover:underline dark:text-red-400"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td class="px-4 py-6 text-center text-gray-500" colspan="6">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
</div>
