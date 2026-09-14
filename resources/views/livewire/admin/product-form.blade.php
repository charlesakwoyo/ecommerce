<div class="max-w-2xl">
    <h1 class="mb-6 text-xl font-semibold">{{ $product ? 'Edit product' : 'New product' }}</h1>

    <form wire:submit="save" class="space-y-4 rounded-lg border border-gray-200 p-6 dark:border-gray-800">
        <x-input name="name" label="Name" wire:model.live="name" :error="$errors->first('name')" />
        <x-input name="slug" label="Slug" wire:model="slug" :error="$errors->first('slug')" />

        <div>
            <label for="category_id" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
            <select id="category_id" wire:model="category_id" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800">
                <option value="">No category</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            @error('category_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="description" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
            <textarea id="description" wire:model="description" rows="5" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800"></textarea>
            @error('description') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-3 gap-4">
            <x-input name="price" label="Price (USD)" wire:model="price" :error="$errors->first('price')" />
            <x-input name="stock" type="number" label="Stock" wire:model="stock" :error="$errors->first('stock')" />
            <x-input name="sku" label="SKU" wire:model="sku" :error="$errors->first('sku')" />
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" wire:model="is_active" class="rounded border-gray-300">
            Active (visible in the store)
        </label>

        @if ($product && $product->images->isNotEmpty())
            <div>
                <p class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Current images</p>
                <div class="flex flex-wrap gap-3">
                    @foreach ($product->images as $image)
                        <div class="relative h-20 w-20 overflow-hidden rounded-md border border-gray-200 dark:border-gray-700">
                            <img src="{{ $image->url }}" class="h-full w-full object-cover">
                            <button
                                type="button"
                                wire:click="removeImage({{ $image->id }})"
                                wire:confirm="Remove this image?"
                                class="absolute right-0 top-0 rounded-bl bg-red-600 px-1.5 py-0.5 text-xs text-white"
                            >
                                &times;
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div>
            <label for="newImages" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Add images</label>
            <input id="newImages" type="file" wire:model="newImages" multiple accept="image/*" class="block w-full text-sm">
            @error('newImages.*') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

            @if ($newImages)
                <div class="mt-3 flex flex-wrap gap-3">
                    @foreach ($newImages as $image)
                        <img src="{{ $image->temporaryUrl() }}" class="h-20 w-20 rounded-md object-cover">
                    @endforeach
                </div>
            @endif
        </div>

        <div class="flex items-center gap-3">
            <x-button wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">{{ $product ? 'Save changes' : 'Create product' }}</span>
                <span wire:loading wire:target="save">Saving&hellip;</span>
            </x-button>
            <a href="{{ route('admin.products.index') }}" wire:navigate class="text-sm text-gray-600 hover:underline dark:text-gray-400">Cancel</a>
        </div>
    </form>
</div>
