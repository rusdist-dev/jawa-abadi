<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Region;
use Illuminate\Http\JsonResponse;

class CatalogFilterController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $price = Product::active()->selectRaw('MIN(price) as min, MAX(price) as max')->first();

        return response()->json(['data' => [
            'categories' => Category::orderBy('sort_order')->get(['name', 'slug']),
            'regions'    => Region::orderBy('name')->get(['name', 'slug']),
            'price'      => ['min' => (int) $price->min, 'max' => (int) $price->max],
            'sorts'      => collect(Product::SORTS)->map(fn ($label, $value) => compact('value', 'label'))->values(),
        ]]);
    }
}
