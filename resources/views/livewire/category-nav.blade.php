<nav @class(['hidden border-b border-gray-200 bg-white sm:block' => $allCategories->isNotEmpty()])>
    @if ($allCategories->isNotEmpty())
        <div class="mx-auto flex max-w-7xl items-stretch gap-6 px-4 sm:px-6">
            <div class="relative shrink-0" x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false">
                <button
                    type="button"
                    @click="open = ! open"
                    class="flex h-full items-center gap-2 border-r border-gray-200 py-2.5 pr-6 text-sm font-semibold text-navy-900"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd" d="M3 4.5A1.5 1.5 0 0 1 4.5 3h15A1.5 1.5 0 0 1 21 4.5v.75A1.5 1.5 0 0 1 19.5 6h-15A1.5 1.5 0 0 1 3 4.5v-.75Zm0 6A1.5 1.5 0 0 1 4.5 9h15A1.5 1.5 0 0 1 21 10.5v.75a1.5 1.5 0 0 1-1.5 1.5h-15A1.5 1.5 0 0 1 3 11.25v-.75Zm0 6a1.5 1.5 0 0 1 1.5-1.5h15a1.5 1.5 0 0 1 1.5 1.5v.75a1.5 1.5 0 0 1-1.5 1.5h-15a1.5 1.5 0 0 1-1.5-1.5v-.75Z" clip-rule="evenodd" />
                    </svg>
                    All Categories
                </button>

                <div
                    x-show="open"
                    x-cloak
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="absolute top-full left-0 z-50 flex w-72 rounded-b-md border border-t-0 border-gray-200 bg-white shadow-lg"
                >
                    <ul class="max-h-[28rem] w-full divide-y divide-gray-100 overflow-y-auto py-1">
                        @foreach ($allCategories as $category)
                            <li class="group relative">
                                <a
                                    href="{{ route('products.index', ['category' => $category->slug]) }}"
                                    wire:navigate
                                    class="flex items-center justify-between px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-brand-600"
                                >
                                    <span>{{ $category->name }}</span>
                                    <span class="flex items-center gap-1.5 text-xs text-gray-400">
                                        {{ $category->products_count }}
                                        @if ($category->children->isNotEmpty())
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3.5 w-3.5">
                                                <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 .02-1.06L11.168 10 7.23 6.29a.75.75 0 1 1 1.04-1.08l4.5 4.25a.75.75 0 0 1 0 1.08l-4.5 4.25a.75.75 0 0 1-1.06-.02Z" clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </span>
                                </a>

                                @if ($category->children->isNotEmpty())
                                    <div class="absolute top-0 left-full z-50 hidden min-h-full w-64 border border-gray-200 bg-white p-4 shadow-lg group-hover:block">
                                        <p class="mb-2 text-xs font-semibold tracking-wide text-gray-400 uppercase">{{ $category->name }}</p>
                                        <ul class="space-y-1.5">
                                            @foreach ($category->children as $child)
                                                <li>
                                                    <a href="{{ route('products.index', ['category' => $child->slug]) }}" wire:navigate class="block text-sm text-gray-600 hover:text-brand-600">
                                                        {{ $child->name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="flex items-center gap-6 overflow-x-auto py-2.5 text-sm font-medium text-gray-700">
                @foreach ($quickLinks as $category)
                    <a
                        href="{{ route('products.index', ['category' => $category->slug]) }}"
                        wire:navigate
                        class="shrink-0 hover:text-brand-600"
                    >
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</nav>
