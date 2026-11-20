@props(['src', 'alt', 'eager' => false, 'dim' => 'false'])

{{--
    Gambar produk; bila belum ada foto tampil placeholder bermerek.
    $src / $alt / $dim : ekspresi Alpine ($dim = true meredupkan gambar, mis. stok habis).
    Atur bentuk lewat class pemanggil (aspect-*, rounded-*). Induk bergrup `group` → zoom saat hover.
--}}
<div {{ $attributes->merge(['class' => 'relative overflow-hidden bg-primary-50']) }}>
    <template x-if="{!! $src !!}">
        <img x-bind:src="{!! $src !!}" x-bind:alt="{!! $alt !!}" @if (! $eager) loading="lazy" @endif
             x-bind:class="{ 'opacity-60': {!! $dim !!} }"
             class="absolute inset-0 size-full object-cover transition-transform duration-300 ease-out group-hover:scale-105 motion-reduce:transition-none">
    </template>
    <template x-if="!({!! $src !!})">
        <div class="absolute inset-0 grid place-items-center bg-gradient-to-br from-primary-50 to-primary-100 text-primary-600/60">
            <x-ui.icon name="leaf" size="size-12" />
        </div>
    </template>
    {{ $slot }}
</div>
