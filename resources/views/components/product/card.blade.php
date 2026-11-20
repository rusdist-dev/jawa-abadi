{{--
    Kartu produk. Harus berada di dalam <template x-for="(product, index) in …">:
    memakai variabel `product` (objek ProductResource) dan `index` (untuk animasi bertahap).
--}}
<article class="group relative flex animate-rise flex-col gap-3"
         x-bind:style="{ animationDelay: (Math.min(index % 12, 8) * 45) + 'ms' }">
    <x-product.image src="product.image_url" alt="product.name" dim="!product.in_stock"
                     class="aspect-[4/5] rounded-md border border-stone-200">
        <div class="absolute left-2.5 top-2.5 flex flex-col items-start gap-1.5">
            <x-product.stock-badge />
        </div>
    </x-product.image>

    <div class="flex flex-col gap-1">
        <div class="flex items-baseline justify-between gap-3">
            <h3 class="text-sm font-medium text-pretty">
                <a x-bind:href="product.url" class="after:absolute after:inset-0 focus-visible:outline-none focus-visible:after:ring-2 focus-visible:after:ring-primary-500 focus-visible:after:rounded-md"
                   x-text="product.name"></a>
            </h3>
            <span class="shrink-0 text-sm font-semibold tabular-nums">
                <span x-text="$rupiah(product.price)"></span><span class="text-xs font-normal text-stone-500">/<span x-text="product.unit"></span></span>
            </span>
        </div>
        <span class="text-xs text-stone-500" x-text="[product.category?.name, product.region?.name].filter(Boolean).join(' · ')"></span>
        <span class="flex items-center gap-1.5 text-xs text-stone-500">
            <x-ui.rating value="product.rating" />
            <span class="tabular-nums" x-text="product.rating.toFixed(1)"></span>
            <span class="tabular-nums" x-text="'(' + $number(product.reviews_count) + ')'"></span>
        </span>
    </div>
</article>
