<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    public const SORTS = [
        'featured'   => 'Unggulan',
        'newest'     => 'Terbaru',
        'price-asc'  => 'Harga terendah',
        'price-desc' => 'Harga tertinggi',
        'rating'     => 'Rating tertinggi',
    ];

    public const LOW_STOCK = 10;

    protected $fillable = [
        'category_id', 'region_id', 'name', 'slug', 'summary', 'description',
        'price', 'unit', 'min_order', 'stock', 'rating', 'reviews_count',
        'images', 'specs', 'is_featured', 'is_active',
    ];

    protected $casts = [
        'images'      => 'array',
        'specs'       => 'array',
        'rating'      => 'float',
        'is_featured' => 'boolean',
        'is_active'   => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function priceHistories(): HasMany
    {
        return $this->hasMany(PriceHistory::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Terapkan filter katalog. $ignore berisi nama filter yang dilewati
     * (dipakai untuk menghitung facet kategori tanpa filter kategori).
     */
    public function scopeFilter(Builder $query, array $f, array $ignore = []): Builder
    {
        $skip = fn (string $key) => in_array($key, $ignore, true);

        return $query
            ->when(! empty($f['q']), fn ($q) => $q->where(function ($q) use ($f) {
                $term = '%' . addcslashes($f['q'], '%_\\') . '%';
                $q->where('name', 'like', $term)->orWhere('summary', 'like', $term);
            }))
            ->when(! $skip('category') && ! empty($f['category']),
                fn ($q) => $q->whereHas('category', fn ($c) => $c->whereIn('slug', $f['category'])))
            ->when(! empty($f['region']),
                fn ($q) => $q->whereHas('region', fn ($r) => $r->whereIn('slug', $f['region'])))
            ->when(isset($f['min_price']), fn ($q) => $q->where('price', '>=', (int) $f['min_price']))
            ->when(isset($f['max_price']), fn ($q) => $q->where('price', '<=', (int) $f['max_price']))
            ->when(! empty($f['in_stock']), fn ($q) => $q->where('stock', '>', 0))
            ->when(! empty($f['min_rating']), fn ($q) => $q->where('rating', '>=', (float) $f['min_rating']));
    }

    public function scopeSortBy(Builder $query, ?string $sort): Builder
    {
        return match ($sort) {
            'price-asc'  => $query->orderBy('price')->orderBy('id'),
            'price-desc' => $query->orderByDesc('price')->orderBy('id'),
            'newest'     => $query->orderByDesc('created_at')->orderByDesc('id'),
            'rating'     => $query->orderByDesc('rating')->orderByDesc('reviews_count')->orderBy('id'),
            default      => $query->orderByDesc('is_featured')->orderByDesc('reviews_count')->orderBy('id'),
        };
    }
}
