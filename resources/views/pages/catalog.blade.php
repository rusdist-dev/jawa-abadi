<x-layout.app title="Katalog">
    <x-catalog.banner-carousel />

    <section x-data="catalog" class="py-12 md:py-20">
        <div class="mx-auto flex w-full max-w-7xl flex-col gap-10 px-6 md:px-10">

            {{-- <div class="flex max-w-xl flex-col gap-4">
                <span class="animate-rise text-xs uppercase tracking-wide text-primary-700">Belanja</span>
                <h1 class="animate-rise text-balance text-3xl font-semibold tracking-tight sm:text-4xl" style="animation-delay: 80ms">
                    Hasil kawasan konservasi
                </h1>
                <p class="animate-rise text-base leading-relaxed text-stone-600" style="animation-delay: 160ms">
                    Bibit mangrove, olahan buah mangrove, kepiting, udang, ikan, dan lobster dari petambak
                    dan nelayan di sekitar kawasan konservasi.
                </p>
            </div> --}}

            <div class="grid gap-10 lg:grid-cols-[15rem_minmax(0,1fr)] lg:gap-14">

                {{-- Filter desktop --}}
                <aside aria-label="Filter" class="hidden animate-rise lg:sticky lg:top-24 lg:block lg:self-start" style="animation-delay: 240ms">
                    <div class="flex items-center justify-between border-b border-stone-200 pb-3">
                        <span class="text-sm font-medium">Filter</span>
                        <x-ui.button variant="ghost" size="sm" class="text-stone-500"
                                     x-on:click="clearAll()" x-bind:disabled="active.length === 0">Hapus semua</x-ui.button>
                    </div>
                    <div class="pt-4">
                        <x-catalog.filter-panel prefix="d" />
                    </div>
                </aside>

                {{-- Hasil --}}
                <div class="flex min-w-0 animate-rise flex-col gap-6" style="animation-delay: 320ms">
                    <div class="flex flex-col gap-4 border-b border-stone-200 pb-4">
                        <label class="relative block">
                            <span class="sr-only">Cari produk</span>
                            <x-ui.icon name="search" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-stone-400" />
                            <input type="search" x-model="filters.q" placeholder="Cari produk, mis. kepiting bakau"
                                   class="h-10 w-full rounded-md border border-stone-300 bg-white pl-9 pr-3 text-sm placeholder:text-stone-400 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/30">
                        </label>

                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <x-ui.button variant="outline" size="sm" class="lg:hidden" x-on:click="sheetOpen = true">
                                    <x-ui.icon name="sliders" />
                                    Filter
                                    <span x-show="active.length > 0" x-cloak class="tabular-nums" x-text="'(' + active.length + ')'"></span>
                                </x-ui.button>
                                <p aria-live="polite" class="text-sm tabular-nums text-stone-600">
                                    <template x-if="list.loaded"><span x-text="$number(total) + ' produk'"></span></template>
                                    <template x-if="list.loading && items.length"><span>Memperbarui…</span></template>
                                </p>
                            </div>

                            <label class="flex items-center gap-2 text-sm">
                                <span class="sr-only">Urutkan produk</span>
                                <select x-model="filters.sort" aria-label="Urutkan produk"
                                        class="h-9 min-w-44 rounded-md border border-stone-300 bg-white py-0 pl-3 pr-8 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/30">
                                    <template x-for="s in sorts" x-bind:key="s.value">
                                        <option x-bind:value="s.value" x-text="s.label" x-bind:selected="s.value === filters.sort"></option>
                                    </template>
                                </select>
                            </label>
                        </div>

                        {{-- Chip filter aktif --}}
                        <ul x-show="active.length > 0" x-cloak aria-label="Filter aktif" class="flex flex-wrap items-center gap-2">
                            <template x-for="f in active" x-bind:key="f.key">
                                <li class="animate-pop">
                                    <span class="inline-flex h-7 items-center gap-1 rounded-full border border-stone-300 bg-white pl-2.5 pr-1 text-xs">
                                        <span x-text="f.label"></span>
                                        <button type="button" x-on:click="f.remove()" x-bind:aria-label="'Hapus ' + f.label"
                                                class="grid size-5 place-items-center rounded-full text-stone-500 transition-colors hover:bg-stone-100 hover:text-stone-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500">
                                            <x-ui.icon name="x" size="size-3" />
                                        </button>
                                    </span>
                                </li>
                            </template>
                            <li>
                                <x-ui.button variant="link" class="text-xs text-stone-500 hover:text-stone-900" x-on:click="clearAll()">Hapus semua</x-ui.button>
                            </li>
                        </ul>
                    </div>

                    {{-- State: loading --}}
                    <div x-show="showSkeleton" class="grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-4 sm:gap-x-6" aria-busy="true" aria-label="Memuat produk">
                        @for ($i = 0; $i < 6; $i++)
                            <x-product.card-skeleton />
                        @endfor
                    </div>

                    {{-- State: error --}}
                    <x-ui.error-state x-show="failed" x-cloak message="errorMessage" retry="retry()" title="Produk gagal dimuat" />

                    {{-- State: success --}}
                    <div x-show="showGrid" x-cloak class="flex flex-col gap-10 transition-opacity" x-bind:class="{ 'opacity-60': list.loading }">
                        <div class="grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-4 sm:gap-x-6">
                            <template x-for="(product, index) in items" x-bind:key="product.id">
                                <x-product.card />
                            </template>
                        </div>

                        <div x-show="hasMore || more.loading || more.failed" class="flex flex-col items-center gap-3">
                            <p x-show="more.failed" x-cloak class="text-sm text-danger-700" x-text="more.error?.message"></p>
                            <x-ui.button variant="outline" x-on:click="loadMore()" x-bind:disabled="more.loading">
                                <span x-show="!more.loading && !more.failed">Muat lebih banyak</span>
                                <span x-show="more.loading" x-cloak>Memuat…</span>
                                <span x-show="more.failed" x-cloak>Coba lagi</span>
                            </x-ui.button>
                        </div>
                    </div>

                    {{-- State: kosong --}}
                    <x-ui.empty x-show="showEmpty" x-cloak title="Tidak ada produk yang cocok"
                                description="Coba perluas rentang harga atau kurangi filter. Stok produk panen diperbarui secara berkala.">
                        <x-ui.button variant="outline" x-on:click="clearAll()">Hapus filter</x-ui.button>
                    </x-ui.empty>
                </div>
            </div>
        </div>

        {{-- Filter mobile --}}
        <x-ui.sheet model="sheetOpen" title="Filter" description="Hasil diperbarui saat Anda mengubahnya.">
            <x-catalog.filter-panel prefix="m" />
            <x-slot:footer>
                <x-ui.button variant="outline" x-on:click="clearAll()" x-bind:disabled="active.length === 0">Hapus semua</x-ui.button>
                <x-ui.button class="flex-1 tabular-nums" x-on:click="sheetOpen = false">
                    <span x-text="total === 0 && list.loaded ? 'Tidak ada hasil' : 'Lihat ' + $number(total) + ' produk'"></span>
                </x-ui.button>
            </x-slot:footer>
        </x-ui.sheet>
    </section>
</x-layout.app>
