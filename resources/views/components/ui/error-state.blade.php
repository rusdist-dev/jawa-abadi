@props(['message' => 'error?.message', 'retry' => 'retry()', 'title' => 'Gagal memuat data'])

{{--
    State galat dengan tombol coba lagi. Ditampilkan oleh pemanggil lewat x-show="...failed".
    $message / $retry : ekspresi Alpine.
--}}
<div role="alert" {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center gap-5 rounded-lg border border-danger-200 bg-danger-50/60 px-6 py-14 text-center']) }}>
    <span class="grid size-11 place-items-center rounded-lg bg-danger-100 text-danger-700">
        <x-ui.icon name="alert" size="size-6" />
    </span>
    <div class="flex max-w-sm flex-col gap-1.5">
        <p class="text-lg font-semibold tracking-tight text-danger-900">{{ $title }}</p>
        <p class="text-sm leading-relaxed text-danger-800" x-text="{!! $message !!} || 'Terjadi kesalahan. Silakan coba lagi.'"></p>
    </div>
    <x-ui.button variant="outline" x-on:click="{!! $retry !!}">
        <x-ui.icon name="refresh" /> Coba lagi
    </x-ui.button>
</div>
