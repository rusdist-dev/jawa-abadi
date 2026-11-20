@props(['label', 'value', 'tone' => "''"])

{{--
    Kartu angka ringkas. $value = ekspresi Alpine (teks), $tone = ekspresi Alpine berisi class warna teks.
--}}
<div {{ $attributes->merge(['class' => 'rounded-lg border border-stone-200 p-4']) }}>
    <dt class="text-xs text-stone-500">{{ $label }}</dt>
    <dd class="mt-1 text-lg font-semibold tabular-nums" x-bind:class="{!! $tone !!}" x-text="{!! $value !!}"></dd>
</div>
