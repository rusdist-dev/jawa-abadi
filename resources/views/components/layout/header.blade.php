<header class="sticky top-0 z-40 border-b border-stone-200 bg-white/90 backdrop-blur">
    <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-6 md:px-10">
        <a href="{{ route('catalog') }}" aria-label="{{ config('app.name') }} — beranda" class="flex items-center rounded-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500">
            <img src="{{ asset('images/logo/logo-header.png') }}" alt="{{ config('app.name') }}" width="313" height="120" class="h-10 w-auto">
        </a>

        <nav aria-label="Utama" class="flex items-center gap-1 text-sm">
            <x-ui.button :href="route('catalog')" variant="ghost" size="sm">Katalog</x-ui.button>
        </nav>
    </div>
</header>
