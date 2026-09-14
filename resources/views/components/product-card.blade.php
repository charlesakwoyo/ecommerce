@props(['product'])

<a href="{{ route('products.show', $product) }}" wire:navigate class="group block overflow-hidden rounded-lg border border-gray-200 bg-white transition hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
    <div class="aspect-square overflow-hidden bg-gray-100 dark:bg-gray-800">
        @if ($product->images->first())
            <img
                src="{{ $product->images->first()->url }}"
                alt="{{ $product->name }}"
                class="h-full w-full object-cover transition group-hover:scale-105"
                loading="lazy"
            >
        @else
            <div class="flex h-full w-full items-center justify-center text-sm text-gray-400">No image</div>
        @endif
    </div>

    <div class="p-4">
        <h3 class="truncate text-sm font-medium text-gray-900 dark:text-gray-100">{{ $product->name }}</h3>
        <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">${{ number_format($product->price / 100, 2) }}</p>

        @if (! $product->isInStock())
            <p class="mt-1 text-xs font-medium text-red-600 dark:text-red-400">Out of stock</p>
        @endif
    </div>
</a>
