@props(['title'])

{{-- Satu grup filter dengan judul; antar grup dipisah garis oleh induk (divide-y). --}}
<div role="group" aria-label="{{ $title }}" {{ $attributes->merge(['class' => 'flex flex-col gap-2 py-3.5 first:pt-0 last:pb-0']) }}>
    <p class="text-xs uppercase tracking-wide text-stone-500" aria-hidden="true">{{ $title }}</p>
    {{ $slot }}
</div>
