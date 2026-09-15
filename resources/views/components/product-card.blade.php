@props(['product'])

<a href="{{ route('products.show', $product) }}" wire:navigate class="group block overflow-hidden rounded-md border border-gray-200 bg-white transition hover:shadow-lg dark:border-gray-800 dark:bg-gray-900">
    <div class="relative aspect-square overflow-hidden bg-gray-100 dark:bg-gray-800">
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

        @unless ($product->isInStock())
            <span class="absolute top-2 left-2 rounded bg-gray-900/80 px-2 py-1 text-[11px] font-bold tracking-wide text-white uppercase">
                Out of stock
            </span>
        @endunless
    </div>

    <div class="p-3">
        <h3 class="line-clamp-2 min-h-10 text-sm text-gray-700 dark:text-gray-300">{{ $product->name }}</h3>
        <p class="mt-2 text-base font-extrabold text-brand-600 dark:text-brand-400"><x-money :amount="$product->price" /></p>
    </div>
</a>
