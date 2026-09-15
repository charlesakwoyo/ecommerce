<nav @class(['hidden border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 sm:block' => $categories->isNotEmpty()])>
    @if ($categories->isNotEmpty())
        <div class="mx-auto flex max-w-7xl items-center gap-6 overflow-x-auto px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 sm:px-6">
            @foreach ($categories as $category)
                <a
                    href="{{ route('products.index', ['category' => $category->slug]) }}"
                    wire:navigate
                    class="shrink-0 hover:text-brand-600 dark:hover:text-brand-400"
                >
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    @endif
</nav>
