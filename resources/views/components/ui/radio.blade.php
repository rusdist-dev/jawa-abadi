@props(['id', 'name', 'value', 'model'])

{{-- Radio dengan label (slot). $id / $value / $model = ekspresi Alpine; $model diikat dua arah (x-model). --}}
<div class="flex items-center gap-2.5">
    <input type="radio" name="{{ $name }}" x-bind:id="{!! $id !!}" x-bind:value="{!! $value !!}" x-model="{!! $model !!}"
           {{ $attributes->merge(['class' => 'size-4 shrink-0 cursor-pointer border-stone-400 text-primary-600 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2']) }}>
    <label x-bind:for="{!! $id !!}" class="flex flex-1 cursor-pointer items-center gap-2 text-sm">{{ $slot }}</label>
</div>
