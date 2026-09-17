<div class="space-y-10">
    <section class="relative overflow-hidden rounded-md bg-gradient-to-br from-navy-900 via-navy-900 to-navy-800">
        <div class="pointer-events-none absolute -top-16 -right-16 h-72 w-72 rounded-full bg-brand-500/20 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 left-1/3 h-64 w-64 rounded-full bg-brand-400/10 blur-3xl"></div>

        <div class="relative grid items-center gap-8 px-6 py-14 sm:px-10 sm:py-20 lg:grid-cols-2 lg:gap-12">
            <div class="text-center lg:text-left">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-brand-500/15 px-3 py-1 text-xs font-semibold tracking-wide text-brand-400 uppercase">
                    Big savings, every day
                </span>
                <h1 class="mt-4 text-3xl font-extrabold text-white sm:text-5xl">
                    Shop everything you need, <span class="text-brand-400">delivered fast</span>
                </h1>
                <p class="mx-auto mt-4 max-w-md text-gray-300 lg:mx-0">
                    Phones, fashion, groceries and more &mdash; quality products at honest prices, with simple checkout and fast delivery countrywide.
                </p>
                <div class="mt-7 flex flex-wrap items-center justify-center gap-3 lg:justify-start">
                    <a href="{{ route('products.index') }}" wire:navigate class="inline-block rounded-md bg-brand-500 px-6 py-2.5 text-sm font-bold text-white hover:bg-brand-600">
                        Shop now
                    </a>
                    @if ($categories->isNotEmpty())
                        <a href="{{ route('products.index', ['category' => $categories->first()->slug]) }}" wire:navigate class="inline-block rounded-md border border-white/25 px-6 py-2.5 text-sm font-bold text-white hover:bg-white/10">
                            Explore categories
                        </a>
                    @endif
                </div>
            </div>

            <div class="relative hidden lg:block">
                <div class="grid grid-cols-2 gap-4">
                    @foreach ($products->take(4) as $product)
                        <a
                            href="{{ route('products.show', $product) }}"
                            wire:navigate
                            class="rounded-lg bg-white/95 p-3 shadow-lg transition hover:-translate-y-1 {{ $loop->even ? 'mt-6' : '' }}"
                        >
                            <div class="aspect-square overflow-hidden rounded-md bg-gray-100">
                                <x-product-image :product="$product" />
                            </div>
                            <p class="mt-2 truncate text-xs font-medium text-gray-700">{{ $product->name }}</p>
                            <p class="text-sm font-extrabold text-brand-600"><x-money :amount="$product->price" /></p>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="relative grid grid-cols-2 gap-px border-t border-white/10 bg-white/5 text-white sm:grid-cols-4">
            @foreach ([
                ['label' => 'Fast delivery', 'sub' => 'Countrywide shipping', 'icon' => 'M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.5m-9-9h9.5a1 1 0 0 1 .8.4l2.7 3.6a1 1 0 0 1 .2.6v3.4a1 1 0 0 1-1 1h-1m-10.5 0H4.5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h9a1 1 0 0 1 1 1v8.25'],
                ['label' => 'Secure payment', 'sub' => 'Stripe-powered checkout', 'icon' => 'M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z'],
                ['label' => 'Easy returns', 'sub' => 'Hassle-free process', 'icon' => 'M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99'],
                ['label' => 'Quality goods', 'sub' => 'Trusted local sellers', 'icon' => 'M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.286Z'],
            ] as $badge)
                <div class="flex items-center gap-3 bg-navy-900 px-5 py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7 shrink-0 text-brand-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $badge['icon'] }}" />
                    </svg>
                    <div>
                        <p class="text-sm font-semibold">{{ $badge['label'] }}</p>
                        <p class="text-xs text-gray-400">{{ $badge['sub'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    @if ($categories->isNotEmpty())
        <section>
            <h2 class="mb-4 text-lg font-bold text-gray-900 dark:text-gray-100">Shop by category</h2>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                @foreach ($categories as $category)
                    <a
                        href="{{ route('products.index', ['category' => $category->slug]) }}"
                        wire:navigate
                        class="rounded-md border border-gray-200 bg-white p-4 text-center text-sm font-medium hover:border-brand-400 hover:text-brand-600 dark:border-gray-800 dark:bg-gray-900 dark:hover:border-brand-700"
                    >
                        {{ $category->name }}
                        <span class="block text-xs font-normal text-gray-400">{{ $category->products_count }} items</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <section>
        <div class="mb-4 flex items-center justify-between border-b-2 border-brand-500 pb-2">
            <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">Newest products</h2>
            <a href="{{ route('products.index') }}" wire:navigate class="text-sm font-semibold text-brand-600 hover:underline dark:text-brand-400">View all</a>
        </div>

        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>
</div>
