<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class ProductDetailResource extends ProductResource
{
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'description' => $this->description,
            'images'      => $this->images ?? [],
            'specs'       => collect($this->specs ?? [])
                ->map(fn ($value, $label) => ['label' => $label, 'value' => $value])
                ->values(),
            'region'      => $this->region ? [
                'name'     => $this->region->name,
                'slug'     => $this->region->slug,
                'location' => $this->region->location,
            ] : null,
        ]);
    }
}
