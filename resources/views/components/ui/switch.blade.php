@props(['id', 'model'])

{{-- Saklar on/off dengan label (slot). $model = ekspresi Alpine boolean. --}}
<div class="flex items-center justify-between gap-3">
    <label for="{{ $id }}" class="cursor-pointer text-sm">{{ $slot }}</label>
    <button type="button" role="switch" id="{{ $id }}"
            x-on:click="{!! $model !!} = !{!! $model !!}"
            x-bind:aria-checked="{!! $model !!}"
            x-bind:class="{!! $model !!} ? 'bg-primary-600' : 'bg-stone-300'"
            class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer items-center rounded-full transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2">
        <span class="pointer-events-none block size-5 rounded-full bg-white shadow transition-transform"
              x-bind:class="{!! $model !!} ? 'translate-x-[22px]' : 'translate-x-0.5'"></span>
    </button>
</div>
