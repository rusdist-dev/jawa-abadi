<x-layout.app title="Produk">
    <section x-data="productDetail(@js($slug), @js(config('shop.whatsapp')))" class="py-10 md:py-16">
        <div class="mx-auto flex w-full max-w-6xl flex-col gap-12 px-6 md:px-10">

            {{-- Breadcrumb --}}
            <nav aria-label="Breadcrumb" class="flex flex-wrap items-center gap-1.5 text-sm text-stone-500">
                <a href="{{ route('catalog') }}" class="hover:text-stone-900">Katalog</a>
                <x-ui.icon name="chevron-right" size="size-3.5" />
                <template x-if="p?.category">
                    <a x-bind:href="'{{ route('catalog') }}?category[]=' + p.category.slug" class="hover:text-stone-900" x-text="p.category.name"></a>
                </template>
                <template x-if="p?.category"><x-ui.icon name="chevron-right" size="size-3.5" /></template>
                <span class="text-stone-900" x-text="p?.name ?? 'Memuat…'"></span>
            </nav>

            {{-- Loading --}}
            <div x-show="product.loading" class="grid gap-10 lg:grid-cols-2 lg:gap-14" aria-busy="true" aria-label="Memuat produk">
                <x-ui.skeleton class="aspect-square w-full" />
                <div class="flex flex-col gap-4">
                    <x-ui.skeleton class="h-5 w-24" />
                    <x-ui.skeleton class="h-9 w-3/4" />
                    <x-ui.skeleton class="h-4 w-40" />
                    <x-ui.skeleton class="mt-2 h-10 w-48" />
                    <x-ui.skeleton class="h-20 w-full" />
                    <x-ui.skeleton class="h-12 w-full" />
                </div>
            </div>

            {{-- Tidak ditemukan --}}
            <x-ui.empty x-show="notFound" x-cloak title="Produk tidak ditemukan"
                        description="Produk ini mungkin sudah tidak dijual atau tautannya keliru.">
                <x-ui.button :href="route('catalog')">Kembali ke katalog</x-ui.button>
            </x-ui.empty>

            {{-- Galat --}}
            <x-ui.error-state x-show="product.failed && !notFound" x-cloak message="product.error?.message" retry="retry()" title="Produk gagal dimuat" />

            {{-- Konten --}}
            <template x-if="product.loaded">
                <div class="flex flex-col gap-14">
                    <div class="grid gap-10 lg:grid-cols-2 lg:gap-14">

                        {{-- Galeri --}}
                        <div class="flex flex-col gap-3">
                            <x-product.image src="currentImage" alt="p.name" eager dim="!p.in_stock"
                                             class="aspect-square rounded-lg border border-stone-200" />
                            <div x-show="images.length > 1" class="flex gap-2">
                                <template x-for="(img, i) in images" x-bind:key="img">
                                    <button type="button" x-on:click="imageIndex = i" x-bind:aria-label="'Foto ' + (i + 1)"
                                            x-bind:aria-current="i === imageIndex"
                                            class="size-16 overflow-hidden rounded-md border-2 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500"
                                            x-bind:class="i === imageIndex ? 'border-primary-600' : 'border-stone-200 hover:border-stone-400'">
                                        <img x-bind:src="img" alt="" class="size-full object-cover">
                                    </button>
                                </template>
                            </div>
                        </div>

                        {{-- Info & pembelian --}}
                        <div class="flex flex-col gap-6">
                            <div class="flex flex-col gap-3">
                                <div class="flex flex-wrap items-center gap-2">
                                    <x-ui.badge variant="primary" x-show="p.category" x-text="p.category?.name"></x-ui.badge>
                                    <x-product.stock-badge product="p" />
                                </div>
                                <h1 class="text-balance text-3xl font-semibold tracking-tight sm:text-4xl" x-text="p.name"></h1>
                                <div class="flex items-center gap-2 text-sm text-stone-600">
                                    <x-ui.rating value="p.rating" size="size-4" />
                                    <span class="tabular-nums" x-text="p.rating.toFixed(1)"></span>
                                    <span class="tabular-nums" x-text="'(' + $number(p.reviews_count) + ' ulasan)'"></span>
                                </div>
                            </div>

                            <p class="flex items-baseline gap-1.5">
                                <span class="text-3xl font-semibold tabular-nums" x-text="$rupiah(p.price)"></span>
                                <span class="text-stone-500">/ <span x-text="p.unit"></span></span>
                            </p>

                            <p class="leading-relaxed text-stone-600" x-text="p.summary"></p>

                            {{-- Jumlah & total --}}
                            <div class="flex flex-col gap-4 rounded-lg border border-stone-200 p-5">
                                <div class="flex flex-wrap items-center justify-between gap-4">
                                    <div>
                                        <p id="qty-label" class="text-sm font-medium">Jumlah</p>
                                        <p class="text-xs text-stone-500">
                                            Minimal <span x-text="minQty"></span> <span x-text="p.unit"></span>
                                            <template x-if="p.in_stock"><span>· stok <span x-text="$number(p.stock)"></span></span></template>
                                        </p>
                                    </div>
                                    <div class="inline-flex items-center rounded-md border border-stone-300" role="group" aria-labelledby="qty-label">
                                        <button type="button" x-on:click="step(-1)" x-bind:disabled="!canOrder || qty <= minQty" aria-label="Kurangi jumlah"
                                                class="grid size-10 place-items-center rounded-l-md text-stone-600 hover:bg-stone-100 disabled:opacity-40 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500">
                                            <x-ui.icon name="minus" />
                                        </button>
                                        <input type="number" inputmode="numeric" x-bind:min="minQty" x-bind:max="maxQty" x-bind:value="qty" x-bind:disabled="!canOrder"
                                               x-on:change="setQty($event.target.value); $event.target.value = qty"
                                               class="h-10 w-16 border-x border-stone-300 text-center text-sm tabular-nums [appearance:textfield] focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500 [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none"
                                               aria-label="Jumlah">
                                        <button type="button" x-on:click="step(1)" x-bind:disabled="!canOrder || qty >= maxQty" aria-label="Tambah jumlah"
                                                class="grid size-10 place-items-center rounded-r-md text-stone-600 hover:bg-stone-100 disabled:opacity-40 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500">
                                            <x-ui.icon name="plus" />
                                        </button>
                                    </div>
                                </div>

                                <div class="flex items-baseline justify-between border-t border-stone-200 pt-4">
                                    <span class="text-sm text-stone-600">Perkiraan total</span>
                                    <span class="text-xl font-semibold tabular-nums" x-text="$rupiah(total)"></span>
                                </div>

                                <x-ui.button size="lg" class="w-full" href="#" x-bind:href="orderUrl" x-show="canOrder"
                                             target="_blank" rel="noopener">
                                    <x-ui.icon name="message" size="size-5" /> Pesan via WhatsApp
                                </x-ui.button>
                                <p x-show="!canOrder" x-cloak class="rounded-md bg-danger-50 px-3 py-2.5 text-center text-sm font-medium text-danger-800">
                                    Stok sedang habis. Cek kembali saat panen berikutnya.
                                </p>
                            </div>

                            {{-- Asal kawasan --}}
                            <div x-show="p.region" class="flex items-start gap-3 rounded-lg bg-primary-50 p-4 text-sm">
                                <x-ui.icon name="map-pin" size="size-5" class="mt-0.5 text-primary-700" />
                                <div>
                                    <p class="font-medium text-primary-900">Asal: <span x-text="p.region?.name"></span></p>
                                    <p class="text-primary-800" x-text="p.region?.location"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Deskripsi & spesifikasi --}}
                    <div class="grid gap-10 lg:grid-cols-2 lg:gap-14">
                        <section aria-labelledby="desc-title" class="flex flex-col gap-3">
                            <h2 id="desc-title" class="text-lg font-semibold">Deskripsi</h2>
                            <p class="whitespace-pre-line leading-relaxed text-stone-600" x-text="p.description || p.summary"></p>
                        </section>

                        <section aria-labelledby="spec-title" class="flex flex-col gap-3" x-show="p.specs.length">
                            <h2 id="spec-title" class="text-lg font-semibold">Spesifikasi</h2>
                            <dl class="divide-y divide-stone-200 rounded-lg border border-stone-200 text-sm">
                                <template x-for="s in p.specs" x-bind:key="s.label">
                                    <div class="grid grid-cols-[8rem_1fr] gap-4 px-4 py-3">
                                        <dt class="text-stone-500" x-text="s.label"></dt>
                                        <dd class="font-medium" x-text="s.value"></dd>
                                    </div>
                                </template>
                            </dl>
                        </section>
                    </div>
                </div>
            </template>

            {{-- Riwayat harga (Chart.js) --}}
            <section x-show="product.loaded" x-cloak aria-labelledby="price-title" class="flex flex-col gap-4">
                <div>
                    <h2 id="price-title" class="text-lg font-semibold">Riwayat harga</h2>
                    <p class="text-sm text-stone-500">Harga per <span x-text="p?.unit"></span> selama 6 bulan terakhir.</p>
                </div>

                <x-ui.skeleton x-show="history.loading" class="h-72 w-full" />
                <x-ui.error-state x-show="history.failed" x-cloak message="history.error?.message" retry="history.load().then(d => d && drawChart(d))" title="Riwayat harga gagal dimuat" class="py-10" />
                <x-ui.empty x-show="history.loaded && !stats" x-cloak icon="trending" title="Belum ada riwayat harga" class="py-10" />

                <div x-show="history.loaded && stats" x-cloak class="flex flex-col gap-4">
                    <dl class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                        <x-ui.stat label="Terendah" value="$rupiah(stats?.min)" />
                        <x-ui.stat label="Tertinggi" value="$rupiah(stats?.max)" />
                        <x-ui.stat label="Perubahan 6 bulan"
                                   value="(stats?.change > 0 ? '+' : '') + (stats?.change ?? 0).toFixed(1) + '%'"
                                   tone="stats?.change > 0 ? 'text-warning-700' : 'text-primary-700'" />
                    </dl>
                    <div class="relative h-72 rounded-lg border border-stone-200 p-4">
                        <canvas x-ref="chart" role="img" aria-label="Grafik riwayat harga"></canvas>
                    </div>
                </div>
            </section>

            {{-- Produk terkait --}}
            <section x-show="related.loading || (related.loaded && related.data.length)" x-cloak aria-labelledby="related-title" class="flex flex-col gap-6">
                <h2 id="related-title" class="text-lg font-semibold">Produk terkait</h2>

                <div x-show="related.loading" class="grid grid-cols-2 gap-x-4 gap-y-8 sm:gap-x-6 lg:grid-cols-4" aria-busy="true">
                    @for ($i = 0; $i < 4; $i++)
                        <x-product.card-skeleton />
                    @endfor
                </div>

                <div x-show="related.loaded" class="grid grid-cols-2 gap-x-4 gap-y-8 sm:gap-x-6 lg:grid-cols-4">
                    <template x-for="(product, index) in related.data ?? []" x-bind:key="product.id">
                        <x-product.card />
                    </template>
                </div>
            </section>
        </div>
    </section>
</x-layout.app>
