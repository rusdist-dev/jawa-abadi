<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductIndexRequest;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function index(ProductIndexRequest $request): AnonymousResourceCollection
    {
        $filters = $request->validated();

        $products = Product::query()->active()
            ->with(['category:id,name,slug', 'region:id,name,slug'])
            ->filter($filters)
            ->sortBy($filters['sort'] ?? null)
            ->paginate($filters['per_page'] ?? 12)
            ->withQueryString();

        // Jumlah per kategori dihitung dengan filter lain, tanpa filter kategori itu sendiri.
        $facets = Category::query()
            ->withCount(['products as products_count' => fn ($q) => $q->active()->filter($filters, ['category'])])
            ->orderBy('sort_order')
            ->pluck('products_count', 'slug');

        return ProductResource::collection($products)->additional(['facets' => ['category' => $facets]]);
    }

    public function show(string $slug): ProductDetailResource
    {
        $product = Product::active()->with(['category', 'region'])->where('slug', $slug)->firstOrFail();

        return new ProductDetailResource($product);
    }

    public function related(string $slug): AnonymousResourceCollection
    {
        $product = Product::active()->where('slug', $slug)->firstOrFail();

        $related = Product::active()
            ->with(['category:id,name,slug', 'region:id,name,slug'])
            ->whereKeyNot($product->id)
            ->where(fn ($q) => $q->where('category_id', $product->category_id)
                ->orWhere('region_id', $product->region_id))
            ->orderByRaw('category_id = ? desc', [$product->category_id])
            ->orderByDesc('rating')
            ->limit(4)
            ->get();

        return ProductResource::collection($related);
    }

    public function priceHistory(string $slug): JsonResponse
    {
        $product = Product::active()->where('slug', $slug)->firstOrFail();

        $points = $product->priceHistories()->orderBy('recorded_on')->get()
            ->map(fn ($h) => ['date' => $h->recorded_on->toDateString(), 'price' => $h->price]);

        return response()->json(['data' => $points]);
    }
}
