@props(['title', 'description' => null, 'icon' => 'inbox'])

{{-- Empty state. Tombol aksi masuk lewat slot default. --}}
<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center gap-5 rounded-lg border border-dashed border-stone-300 px-6 py-16 text-center']) }}>
    <span class="grid size-11 place-items-center rounded-lg bg-stone-100 text-stone-600">
        <x-ui.icon :name="$icon" size="size-6" />
    </span>
    <div class="flex max-w-sm flex-col gap-1.5">
        <p class="text-lg font-semibold tracking-tight">{{ $title }}</p>
        @if ($description)
            <p class="text-sm leading-relaxed text-stone-600">{{ $description }}</p>
        @endif
    </div>
    @if (! $slot->isEmpty())
        <div class="flex flex-wrap justify-center gap-3">{{ $slot }}</div>
    @endif
</div>
