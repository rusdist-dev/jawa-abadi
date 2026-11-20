@props(['value', 'size' => 'size-3.5', 'static' => false])

{{--
    Bintang rating dengan pengisian parsial (lebar lapisan atas = value/5).
    $value  : ekspresi Alpine (0–5), mis. "product.rating"; bila static=true dianggap angka biasa.
--}}
@php
    $star = 'M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z';
    $width = $static ? "'" . (max(0, min(5, (float) $value)) / 5 * 100) . "%'" : "(Math.max(0, Math.min(5, {$value})) / 5 * 100) + '%'";
@endphp

<span {{ $attributes->merge(['class' => 'relative inline-flex text-stone-300']) }}>
    <span class="inline-flex" aria-hidden="true">
        @for ($i = 0; $i < 5; $i++)
            <svg class="{{ $size }}" viewBox="0 0 24 24" fill="currentColor"><path d="{{ $star }}"/></svg>
        @endfor
    </span>
    <span class="absolute inset-y-0 left-0 inline-flex overflow-hidden text-warning-500" x-bind:style="{ width: {!! $width !!} }" aria-hidden="true">
        @for ($i = 0; $i < 5; $i++)
            <svg class="{{ $size }} shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="{{ $star }}"/></svg>
        @endfor
    </span>
</span>
