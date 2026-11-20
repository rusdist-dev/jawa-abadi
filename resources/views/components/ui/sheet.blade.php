@props(['model', 'title', 'description' => null])

{{--
    Bottom sheet (hanya mobile). $model = ekspresi Alpine boolean (buka/tutup).
    Slot default = isi; slot "footer" = area tombol bawah.
--}}
<div x-show="{!! $model !!}" x-cloak class="fixed inset-0 z-50 lg:hidden" role="dialog" aria-modal="true"
     aria-label="{{ $title }}" x-on:keydown.escape.window="{!! $model !!} = false">
    <div x-show="{!! $model !!}" class="absolute inset-0 bg-stone-900/50"
         x-transition.opacity.duration.200ms x-on:click="{!! $model !!} = false"></div>

    <div x-show="{!! $model !!}" x-trap.noscroll="{!! $model !!}"
         x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
         x-transition:leave="transition duration-200 ease-in" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full"
         class="absolute inset-x-0 bottom-0 flex max-h-[85svh] flex-col rounded-t-xl bg-white shadow-xl">
        <div class="flex items-start justify-between gap-4 border-b border-stone-200 px-6 py-4">
            <div>
                <h2 class="text-lg font-semibold">{{ $title }}</h2>
                @if ($description)
                    <p class="text-sm text-stone-500">{{ $description }}</p>
                @endif
            </div>
            <button type="button" class="-mr-2 grid size-8 place-items-center rounded-full text-stone-500 hover:bg-stone-100 hover:text-stone-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500"
                    x-on:click="{!! $model !!} = false" aria-label="Tutup">
                <x-ui.icon name="x" />
            </button>
        </div>

        <div class="min-h-0 flex-1 overflow-y-auto px-6 py-6">{{ $slot }}</div>

        @isset($footer)
            <div class="flex gap-3 border-t border-stone-200 px-6 py-4">{{ $footer }}</div>
        @endisset
    </div>
</div>
