<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Product */
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'slug'          => $this->slug,
            'name'          => $this->name,
            'summary'       => $this->summary,
            'price'         => $this->price,
            'unit'          => $this->unit,
            'min_order'     => $this->min_order,
            'stock'         => $this->stock,
            'in_stock'      => $this->stock > 0,
            'low_stock'     => $this->stock > 0 && $this->stock <= Product::LOW_STOCK,
            'rating'        => $this->rating,
            'reviews_count' => $this->reviews_count,
            'image_url'     => $this->images[0] ?? null,
            'category'      => $this->whenLoaded('category', fn () => [
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ]),
            'region'        => $this->whenLoaded('region', fn () => $this->region ? [
                'name' => $this->region->name,
                'slug' => $this->region->slug,
            ] : null),
            'url'           => route('products.show', $this->slug),
        ];
    }
}
