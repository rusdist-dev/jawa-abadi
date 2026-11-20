@props(['prefix' => 'f'])

{{--
    Panel filter katalog. Dipakai di sidebar desktop dan bottom sheet mobile (dua kali) → komponen.
    Berjalan di dalam scope Alpine `catalog`. $prefix menjaga id input tetap unik antar pemakaian.
--}}
<div class="flex flex-col divide-y divide-stone-200">
    <div class="flex flex-col gap-3 pb-3.5">
        <x-ui.multi-select id="{{ $prefix }}-category" label="Kategori" items="categories" field="category"
                           placeholder="Semua kategori" counts="facets" />
        <x-ui.multi-select id="{{ $prefix }}-region" label="Kawasan konservasi" items="regions" field="region"
                           placeholder="Semua kawasan" />
    </div>

    <x-ui.filter-section title="Harga" class="gap-3">
        <x-ui.range-slider min="bounds.min" max="bounds.max" step="1000" low="price.lo" high="price.hi" />
        <div class="flex items-center justify-between text-sm tabular-nums">
            <span><span class="sr-only">Dari </span><span x-text="$rupiah(price.lo)"></span></span>
            <span><span class="sr-only">Sampai </span><span x-text="$rupiah(price.hi)"></span></span>
        </div>
    </x-ui.filter-section>

    <div class="py-3.5">
        <x-ui.switch id="{{ $prefix }}-stock" model="filters.in_stock">Hanya yang tersedia</x-ui.switch>
    </div>

    <x-ui.filter-section title="Rating">
        <template x-for="r in ratings" x-bind:key="r.value">
            <x-ui.radio id="'{{ $prefix }}-rating-' + (r.value || 'all')" name="{{ $prefix }}-rating"
                        value="r.value" model="filters.min_rating">
                <template x-if="r.value">
                    <x-ui.rating value="Number(r.value)" />
                </template>
                <span x-text="r.label"></span>
            </x-ui.radio>
        </template>
    </x-ui.filter-section>
</div>
