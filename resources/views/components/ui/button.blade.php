@props([
    'variant' => 'primary', // primary | outline | ghost | danger | link
    'size' => 'md',         // sm | md | lg
    'href' => null,
    'type' => 'button',
])

{{-- Catatan: pada tag komponen gunakan x-on:… / x-bind:… (bukan @ / :) agar tidak dibaca Blade. --}}
@php
    $variants = [
        'primary' => 'bg-primary-600 text-white hover:bg-primary-700',
        'outline' => 'border border-stone-300 bg-white text-stone-900 hover:bg-stone-50',
        'ghost'   => 'text-stone-600 hover:bg-stone-100 hover:text-stone-900',
        'danger'  => 'bg-danger-600 text-white hover:bg-danger-700',
        'link'    => 'text-primary-700 underline-offset-4 hover:underline',
    ];
    $sizes = [
        'sm' => 'h-8 px-3 text-xs',
        'md' => 'h-10 px-4 text-sm',
        'lg' => 'h-12 px-6 text-base',
    ];
    $classes = 'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md font-medium transition-colors '
        . 'focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 '
        . 'disabled:pointer-events-none disabled:opacity-50 '
        . $variants[$variant] . ' ' . ($variant === 'link' ? 'h-auto px-0' : $sizes[$size]);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
