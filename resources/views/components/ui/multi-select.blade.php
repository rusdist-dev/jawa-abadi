@props(['id', 'label', 'items', 'field', 'placeholder' => 'Semua', 'counts' => null])

{{--
    Dropdown pilih banyak (checkbox di dalam panel) — hemat tempat dibanding daftar checkbox terbuka.
    Berjalan di dalam scope Alpine `catalog`:
      $items  : ekspresi array [{slug, name}]
      $field  : kunci di filters (array slug) — dibaca & diubah lewat toggle(field, slug)
      $counts : ekspresi opsional objek {slug: jumlah}; opsi dengan jumlah 0 dinonaktifkan kecuali sudah dipilih
    Panel tampil di alur dokumen (bukan absolute) agar tidak terpotong oleh bottom sheet.
--}}
@php
    $countExpr = $counts ? "{$counts}[o.slug] ?? 0" : null;
    $disabledExpr = $counts ? "({$counts}[o.slug] ?? 0) === 0 && !filters.{$field}.includes(o.slug)" : 'false';
@endphp
<div x-data="{ open: false }" x-on:keydown.escape.stop="open = false" x-on:click.outside="open = false" class="flex flex-col gap-1.5">
    <p class="text-xs uppercase tracking-wide text-stone-500" id="{{ $id }}-label">{{ $label }}</p>

    <button type="button" id="{{ $id }}" x-on:click="open = !open"
            aria-haspopup="listbox" x-bind:aria-expanded="open" aria-labelledby="{{ $id }}-label {{ $id }}"
            class="flex h-10 w-full items-center justify-between gap-2 rounded-md border border-stone-300 bg-white px-3 text-left text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/30">
        <span class="min-w-0 flex-1 truncate" x-bind:class="filters.{{ $field }}.length ? 'text-stone-900' : 'text-stone-500'"
              x-text="filters.{{ $field }}.length
                  ? {!! $items !!}.filter((i) => filters.{{ $field }}.includes(i.slug)).map((i) => i.name).join(', ')
                  : '{{ $placeholder }}'"></span>
        <span x-show="filters.{{ $field }}.length > 0" x-cloak
              class="grid h-5 min-w-5 place-items-center rounded-full bg-primary-600 px-1 text-xs tabular-nums text-white"
              x-text="filters.{{ $field }}.length"></span>
        <x-ui.icon name="chevron-right" size="size-4" class="shrink-0 text-stone-500 transition-transform" x-bind:class="open ? '-rotate-90' : 'rotate-90'" />
    </button>

    <div x-show="open" x-cloak role="listbox" aria-multiselectable="true" aria-labelledby="{{ $id }}-label"
         class="flex max-h-56 flex-col gap-2.5 overflow-y-auto rounded-md border border-stone-200 bg-white p-3">
        <template x-for="o in {!! $items !!}" x-bind:key="o.slug">
            <x-ui.checkbox id="'{{ $id }}-' + o.slug"
                           checked="filters.{{ $field }}.includes(o.slug)"
                           :disabled="$disabledExpr"
                           :count="$countExpr"
                           x-on:change="toggle('{{ $field }}', o.slug)">
                <span x-text="o.name"></span>
            </x-ui.checkbox>
        </template>
    </div>
</div>
