{{-- Carousel iklan; data dari GET /api/banners. Autoplay berhenti saat hover/fokus. --}}
<section x-data="bannerCarousel" x-show="show" x-cloak aria-roledescription="carousel" aria-label="Produk unggulan"
         class="mx-auto w-full max-w-7xl px-6 pt-6 md:px-10"
         x-on:mouseenter="paused = true" x-on:mouseleave="paused = false"
         x-on:focusin="paused = true" x-on:focusout="paused = false">

    <x-ui.skeleton x-show="banners.loading" class="aspect-[2480/432] min-h-28 w-full rounded-xl" />

    <div x-show="banners.loaded" x-cloak class="relative overflow-hidden rounded-xl border border-stone-200 bg-stone-100">
        <div class="flex transition-transform duration-500 ease-out motion-reduce:transition-none"
             x-bind:style="{ transform: `translateX(-${index * 100}%)` }" aria-live="off">
            <template x-for="(b, i) in items" x-bind:key="b.id">
                <a x-bind:href="b.url || '#'" class="block aspect-[2480/432] min-h-28 w-full shrink-0 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-primary-500"
                   role="group" aria-roledescription="slide" x-bind:aria-label="(i + 1) + ' dari ' + items.length + ': ' + b.title"
                   x-bind:aria-hidden="i !== index" x-bind:tabindex="i === index ? 0 : -1">
                    <img x-bind:src="b.image" x-bind:alt="b.title" x-bind:loading="i === 0 ? 'eager' : 'lazy'" class="size-full object-cover">
                </a>
            </template>
        </div>

        <template x-if="items.length > 1">
            <div>
                <button type="button" x-on:click="go(index - 1)" aria-label="Banner sebelumnya"
                        class="absolute left-2 top-1/2 hidden size-8 -translate-y-1/2 place-items-center rounded-full bg-white/85 text-stone-800 shadow backdrop-blur hover:bg-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 sm:grid">
                    <x-ui.icon name="chevron-right" class="rotate-180" />
                </button>
                <button type="button" x-on:click="go(index + 1)" aria-label="Banner berikutnya"
                        class="absolute right-2 top-1/2 hidden size-8 -translate-y-1/2 place-items-center rounded-full bg-white/85 text-stone-800 shadow backdrop-blur hover:bg-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 sm:grid">
                    <x-ui.icon name="chevron-right" />
                </button>

                <div class="absolute inset-x-0 bottom-2 flex justify-center gap-1.5">
                    <template x-for="(b, i) in items" x-bind:key="'dot-' + b.id">
                        <button type="button" x-on:click="go(i)" x-bind:aria-label="'Banner ' + (i + 1)" x-bind:aria-current="i === index"
                                class="h-1.5 rounded-full transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500"
                                x-bind:class="i === index ? 'w-5 bg-white' : 'w-1.5 bg-white/60 hover:bg-white/90'"></button>
                    </template>
                </div>
            </div>
        </template>
    </div>
</section>
