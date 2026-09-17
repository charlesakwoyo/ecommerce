@if ($categories->isNotEmpty())
    <ul class="space-y-2 text-sm text-gray-400">
        @foreach ($categories as $category)
            <li>
                <a href="{{ route('products.index', ['category' => $category->slug]) }}" wire:navigate class="hover:text-brand-400">
                    {{ $category->name }}
                </a>
            </li>
        @endforeach
    </ul>
@endif
