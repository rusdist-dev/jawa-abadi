@props(['variant' => 'neutral']) {{-- neutral | primary | warning | danger | outline --}}

@php
    $variants = [
        'neutral' => 'bg-stone-100 text-stone-700',
        'primary' => 'bg-primary-50 text-primary-800 ring-1 ring-inset ring-primary-600/20',
        'warning' => 'bg-warning-50 text-warning-800 ring-1 ring-inset ring-warning-600/25',
        'danger'  => 'bg-danger-50 text-danger-800 ring-1 ring-inset ring-danger-600/20',
        'outline' => 'border border-stone-300 bg-white/90 text-stone-800 backdrop-blur',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium ' . $variants[$variant]]) }}>
    {{ $slot }}
</span>
