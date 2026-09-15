<div class="space-y-12">
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        <div class="aspect-square overflow-hidden rounded-lg bg-gray-100 dark:bg-gray-800">
            @if ($product->images->first())
                <img src="{{ $product->images->first()->url }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
            @else
                <div class="flex h-full w-full items-center justify-center text-gray-400">No image</div>
            @endif
        </div>

        <div>
            @if ($product->category)
                <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" wire:navigate class="text-sm font-medium text-brand-600 hover:underline dark:text-brand-400">
                    {{ $product->category->name }}
                </a>
            @endif

            <h1 class="mt-1 text-2xl font-semibold">{{ $product->name }}</h1>
            <p class="mt-3 text-2xl font-bold"><x-money :amount="$product->price" /></p>

            <p class="mt-4 whitespace-pre-line text-sm text-gray-600 dark:text-gray-400">{{ $product->description }}</p>

            <div class="mt-6">
                @if ($product->isInStock())
                    <p class="mb-3 text-sm text-green-700 dark:text-green-400">In stock ({{ $product->stock }} available)</p>

                    <form wire:submit="addToCart" class="flex items-end gap-3">
                        <div class="w-24">
                            <label for="quantity" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Qty</label>
                            <input
                                id="quantity"
                                type="number"
                                min="1"
                                max="{{ $product->stock }}"
                                wire:model="quantity"
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800"
                            >
                        </div>

                        <x-button type="submit">
                            <span wire:loading.remove wire:target="addToCart">Add to cart</span>
                            <span wire:loading wire:target="addToCart">Adding&hellip;</span>
                        </x-button>
                    </form>

                    @error('quantity')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                @else
                    <p class="rounded-md bg-red-50 px-4 py-3 text-sm font-medium text-red-700 dark:bg-red-950 dark:text-red-400">
                        This product is currently out of stock.
                    </p>
                @endif
            </div>
        </div>
    </div>

    @if ($related->isNotEmpty())
        <section>
            <h2 class="mb-4 text-lg font-semibold">You might also like</h2>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($related as $item)
                    <x-product-card :product="$item" />
                @endforeach
            </div>
        </section>
    @endif
</div>
