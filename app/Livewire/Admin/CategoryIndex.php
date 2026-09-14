<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Categories')]
class CategoryIndex extends Component
{
    public ?int $editingId = null;

    public string $name = '';

    public string $slug = '';

    public string $description = '';

    public function edit(Category $category): void
    {
        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = (string) $category->description;
    }

    public function create(): void
    {
        $this->reset('editingId', 'name', 'slug', 'description');
        $this->editingId = 0;
    }

    public function cancel(): void
    {
        $this->reset('editingId', 'name', 'slug', 'description');
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:categories,slug,'.$this->editingId.',id'],
            'description' => ['nullable', 'string'],
        ]);

        if ($this->editingId) {
            Category::query()->findOrFail($this->editingId)->update($data);
        } else {
            Category::query()->create($data);
        }

        $this->reset('editingId', 'name', 'slug', 'description');
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }

    public function render(): View
    {
        return view('livewire.admin.category-index', [
            'categories' => Category::query()->withCount('products')->orderBy('name')->get(),
        ]);
    }
}
