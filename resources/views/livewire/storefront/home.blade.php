<div class="space-y-12">
    <section class="rounded-lg bg-indigo-600 px-8 py-16 text-center text-white">
        <h1 class="text-3xl font-bold sm:text-4xl">Welcome to {{ config('app.name') }}</h1>
        <p class="mx-auto mt-3 max-w-xl text-indigo-100">
            Quality products, simple checkout, fast shipping.
        </p>
        <a href="{{ route('products.index') }}" wire:navigate class="mt-6 inline-block rounded-md bg-white px-5 py-2.5 text-sm font-semibold text-indigo-600 hover:bg-indigo-50">
            Shop now
        </a>
    </section>

    @if ($categories->isNotEmpty())
        <section>
            <h2 class="mb-4 text-lg font-semibold">Shop by category</h2>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                @foreach ($categories as $category)
                    <a
                        href="{{ route('products.index', ['category' => $category->slug]) }}"
                        wire:navigate
                        class="rounded-lg border border-gray-200 bg-white p-4 text-center text-sm font-medium hover:border-indigo-300 hover:text-indigo-600 dark:border-gray-800 dark:bg-gray-900 dark:hover:border-indigo-700"
                    >
                        {{ $category->name }}
                        <span class="block text-xs font-normal text-gray-400">{{ $category->products_count }} items</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <section>
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold">Newest products</h2>
            <a href="{{ route('products.index') }}" wire:navigate class="text-sm text-indigo-600 hover:underline dark:text-indigo-400">View all</a>
        </div>

        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>
</div>
