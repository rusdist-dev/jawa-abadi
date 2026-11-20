@props(['product' => 'product'])

{{-- Lencana stok: habis (danger) / menipis (warning). Tidak tampil bila stok aman. $product = ekspresi Alpine. --}}
<template x-if="!{!! $product !!}.in_stock">
    <x-ui.badge variant="danger" {{ $attributes }}>Stok habis</x-ui.badge>
</template>
<template x-if="{!! $product !!}.low_stock">
    <x-ui.badge variant="warning" {{ $attributes }}>Sisa <span x-text="{!! $product !!}.stock"></span> <span x-text="{!! $product !!}.unit"></span></x-ui.badge>
</template>
