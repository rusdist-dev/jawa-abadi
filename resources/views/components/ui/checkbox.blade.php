@props(['id', 'checked', 'disabled' => 'false', 'count' => null])

{{--
    Checkbox dengan label (slot) dan jumlah opsional.
    $id / $checked / $disabled / $count : ekspresi Alpine. Event ditangani lewat atribut x-on:change.
--}}
<div class="flex items-center gap-2.5" x-bind:class="{ 'opacity-50': {!! $disabled !!} }">
    <input type="checkbox" x-bind:id="{!! $id !!}"
           x-bind:checked="{!! $checked !!}" x-bind:disabled="{!! $disabled !!}"
           {{ $attributes->merge(['class' => 'size-4 shrink-0 cursor-pointer rounded border-stone-400 text-primary-600 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:cursor-not-allowed']) }}>
    <label x-bind:for="{!! $id !!}" class="flex-1 cursor-pointer text-sm">{{ $slot }}</label>
    @if ($count)
        <span class="text-xs tabular-nums text-stone-500" x-text="{!! $count !!}"></span>
    @endif
</div>
