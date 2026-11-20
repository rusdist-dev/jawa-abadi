import { api, createResource } from '../lib/api';

const INTERVAL = 5000;

export default () => ({
    banners: createResource(() => api('/banners').then((r) => r.data)),
    index: 0,
    paused: false,
    timer: null,

    async init() {
        await this.banners.load();
        this.start();
    },

    get items() {
        return this.banners.data ?? [];
    },
    get show() {
        // Banner bersifat pelengkap: bila gagal/kosong, seksi disembunyikan tanpa mengganggu katalog.
        return this.banners.loading || this.items.length > 0;
    },

    go(i) {
        const n = this.items.length;
        this.index = (i + n) % n;
        this.start();
    },

    start() {
        clearInterval(this.timer);
        if (this.items.length < 2 || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        this.timer = setInterval(() => {
            if (!this.paused && !document.hidden) this.index = (this.index + 1) % this.items.length;
        }, INTERVAL);
    },
});
