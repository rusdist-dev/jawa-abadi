@props(['min', 'max', 'step' => 1000, 'low', 'high', 'label' => 'Rentang harga'])

{{--
    Slider rentang ganda. Semua prop berupa ekspresi Alpine kecuali $step (angka) dan $label (teks):
    $min/$max = batas, $low/$high = nilai terpilih (dua arah).
--}}
<div class="relative h-5" x-data="{ get span() { return Math.max(1, {!! $max !!} - {!! $min !!}); } }">
    <div class="absolute inset-x-2.5 inset-y-0">
        <div class="absolute inset-x-0 top-1/2 h-1.5 -translate-y-1/2 rounded-full bg-stone-200"></div>
        <div class="absolute top-1/2 h-1.5 -translate-y-1/2 rounded-full bg-primary-600"
             x-bind:style="{ left: (({!! $low !!} - {!! $min !!}) / span * 100) + '%', right: (100 - ({!! $high !!} - {!! $min !!}) / span * 100) + '%' }"></div>
    </div>

    <input type="range" class="range-thumb" aria-label="{{ $label }} minimum"
           x-bind:min="{!! $min !!}" x-bind:max="{!! $max !!}" step="{{ $step }}" x-bind:value="{!! $low !!}"
           x-on:input="{!! $low !!} = Math.min(+$event.target.value, {!! $high !!} - {{ $step }}); $event.target.value = {!! $low !!}">
    <input type="range" class="range-thumb" aria-label="{{ $label }} maksimum"
           x-bind:min="{!! $min !!}" x-bind:max="{!! $max !!}" step="{{ $step }}" x-bind:value="{!! $high !!}"
           x-on:input="{!! $high !!} = Math.max(+$event.target.value, {!! $low !!} + {{ $step }}); $event.target.value = {!! $high !!}">
</div>
