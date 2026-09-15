<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    /**
     * List active products, optionally filtered by search term (`q`) or
     * category slug (`category`), 12 per page.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $products = Product::query()
            ->active()
            ->with(['images', 'category'])
            ->when(
                $request->string('q')->value() !== '',
                fn ($query) => $query->where('name', 'like', '%'.$request->string('q')->value().'%')
            )
            ->when(
                $request->string('category')->value() !== '',
                fn ($query) => $query->whereRelation('category', 'slug', $request->string('category')->value())
            )
            ->orderByDesc('created_at')
            ->paginate(12);

        return ProductResource::collection($products);
    }

    /**
     * Show a single product by its slug.
     */
    public function show(Product $product): ProductResource
    {
        $product->load(['images', 'category']);

        return new ProductResource($product);
    }
}
