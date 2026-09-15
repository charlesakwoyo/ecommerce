<div>
    <div class="mb-6 flex items-center justify-between gap-4">
        <h1 class="text-xl font-semibold">Categories</h1>
        @if ($editingId === null)
            <button wire:click="create" class="rounded-md bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                New category
            </button>
        @endif
    </div>

    @if ($editingId !== null)
        <form wire:submit="save" class="mb-6 space-y-4 rounded-lg border border-gray-200 p-6 dark:border-gray-800">
            <x-input name="name" label="Name" wire:model="name" :error="$errors->first('name')" />
            <x-input name="slug" label="Slug" wire:model="slug" :error="$errors->first('slug')" />

            <div>
                <label for="description" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                <textarea id="description" wire:model="description" rows="3" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800"></textarea>
            </div>

            <div class="flex items-center gap-3">
                <x-button>{{ $editingId ? 'Save changes' : 'Create category' }}</x-button>
                <button type="button" wire:click="cancel" class="text-sm text-gray-600 hover:underline dark:text-gray-400">Cancel</button>
            </div>
        </form>
    @endif

    <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-800">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500 dark:bg-gray-900 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Slug</th>
                    <th class="px-4 py-3">Products</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                @forelse ($categories as $category)
                    <tr wire:key="admin-category-{{ $category->id }}">
                        <td class="px-4 py-3 font-medium">{{ $category->name }}</td>
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $category->slug }}</td>
                        <td class="px-4 py-3">{{ $category->products_count }}</td>
                        <td class="px-4 py-3 text-right">
                            <button wire:click="edit({{ $category->id }})" class="mr-3 text-brand-600 hover:underline dark:text-brand-400">Edit</button>
                            <button
                                wire:click="delete({{ $category->id }})"
                                wire:confirm="Delete this category?"
                                class="text-red-600 hover:underline dark:text-red-400"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td class="px-4 py-6 text-center text-gray-500" colspan="4">No categories yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
