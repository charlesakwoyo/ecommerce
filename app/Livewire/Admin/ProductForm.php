<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin')]
class ProductForm extends Component
{
    use WithFileUploads;

    public ?Product $product = null;

    public ?int $category_id = null;

    public string $name = '';

    public string $slug = '';

    public string $description = '';

    public string $price = '';

    public int $stock = 0;

    public string $sku = '';

    public bool $is_active = true;

    /** @var array<int, TemporaryUploadedFile> */
    public array $newImages = [];

    public function mount(?Product $product = null): void
    {
        if ($product?->exists) {
            $this->product = $product;
            $this->category_id = $product->category_id;
            $this->name = $product->name;
            $this->slug = $product->slug;
            $this->description = (string) $product->description;
            $this->price = number_format($product->price / 100, 2, '.', '');
            $this->stock = $product->stock;
            $this->sku = (string) $product->sku;
            $this->is_active = $product->is_active;
        }
    }

    public function updatedName(string $value): void
    {
        if (! $this->product) {
            $this->slug = Str::slug($value);
        }
    }

    public function save(): void
    {
        $data = $this->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:products,slug,'.($this->product?->id).',id'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'sku' => ['nullable', 'string', 'max:255', 'unique:products,sku,'.($this->product?->id).',id'],
            'is_active' => ['boolean'],
            'newImages.*' => ['image', 'max:4096'],
        ]);

        $data['price'] = (int) round(((float) $data['price']) * 100);

        $product = $this->product
            ? tap($this->product)->update($data)
            : Product::query()->create($data);

        foreach ($this->newImages as $index => $image) {
            $product->images()->create([
                'path' => $image->store('products', 'public'),
                'sort_order' => $index,
            ]);
        }

        session()->flash('status', $this->product ? 'Product updated.' : 'Product created.');

        $this->redirect(route('admin.products.index'), navigate: true);
    }

    public function removeImage(ProductImage $image): void
    {
        abort_unless($this->product && $image->product_id === $this->product->id, 403);

        Storage::disk('public')->delete($image->path);
        $image->delete();
    }

    public function render(): View
    {
        return view('livewire.admin.product-form', [
            'categories' => Category::query()->orderBy('name')->get(),
        ])->title($this->product ? 'Edit product' : 'New product');
    }
}
