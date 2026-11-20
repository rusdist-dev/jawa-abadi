import { api, createResource } from '../lib/api';

const PER_PAGE = 12;
const PRICE_STEP = 1000;

const blankFilters = () => ({
    q: '',
    category: [],
    region: [],
    min_price: null,
    max_price: null,
    in_stock: false,
    min_rating: '',
    sort: 'featured',
});

export default () => ({
    filters: blankFilters(),
    bounds: { min: 0, max: 0 },
    price: { lo: 0, hi: 0 },

    options: createResource(() => api('/catalog/filters').then((r) => r.data)),
    list: createResource((params, opts) => api('/products', { params, ...opts })),
    more: createResource((params, opts) => api('/products', { params, ...opts })),

    items: [],
    meta: null,
    facets: {},
    sheetOpen: false,
    timer: null,
    watching: false,

    ratings: [
        { value: '', label: 'Semua rating' },
        { value: '4', label: '4 bintang ke atas' },
        { value: '3', label: '3 bintang ke atas' },
    ],

    init() {
        this.boot();
    },

    async boot() {
        await this.options.load();
        if (this.options.failed) return;

        this.setBounds();
        this.readUrl();

        // Watcher didaftarkan sekali saja, setelah state awal dari URL terpasang.
        if (!this.watching) {
            this.watching = true;
            this.$watch('price', () => this.syncPrice());
            this.$watch('filters', () => this.schedule());
        }

        this.fetchFirstPage();
    },

    /* ---------- derived ---------- */

    get categories() {
        return this.options.data?.categories ?? [];
    },
    get regions() {
        return this.options.data?.regions ?? [];
    },
    get sorts() {
        return this.options.data?.sorts ?? [];
    },
    get hasMore() {
        return !!this.meta && this.meta.current_page < this.meta.last_page;
    },
    get total() {
        return this.meta?.total ?? 0;
    },
    get showSkeleton() {
        return (this.options.loading || this.list.loading) && this.items.length === 0;
    },
    get showEmpty() {
        return this.list.loaded && this.items.length === 0;
    },
    get showGrid() {
        return !this.list.failed && this.items.length > 0;
    },
    get failed() {
        return this.options.failed || this.list.failed;
    },
    get errorMessage() {
        return (this.options.error || this.list.error)?.message;
    },

    get active() {
        const f = this.filters;
        const out = [];

        if (f.q.trim()) out.push({ key: 'q', label: `“${f.q.trim()}”`, remove: () => (f.q = '') });

        f.category.forEach((slug) =>
            out.push({
                key: `c-${slug}`,
                label: this.categories.find((c) => c.slug === slug)?.name ?? slug,
                remove: () => (f.category = f.category.filter((s) => s !== slug)),
            }),
        );
        f.region.forEach((slug) =>
            out.push({
                key: `r-${slug}`,
                label: this.regions.find((r) => r.slug === slug)?.name ?? slug,
                remove: () => (f.region = f.region.filter((s) => s !== slug)),
            }),
        );

        if (f.min_price !== null || f.max_price !== null) {
            out.push({
                key: 'price',
                label: `${this.$rupiah(this.price.lo)} – ${this.$rupiah(this.price.hi)}`,
                remove: () => this.resetPrice(),
            });
        }
        if (f.in_stock) out.push({ key: 'stock', label: 'Stok tersedia', remove: () => (f.in_stock = false) });
        if (f.min_rating) {
            out.push({
                key: 'rating',
                label: this.ratings.find((r) => r.value === f.min_rating)?.label,
                remove: () => (f.min_rating = ''),
            });
        }

        return out;
    },

    /* ---------- price ---------- */

    setBounds() {
        const { min, max } = this.options.data.price;
        this.bounds = {
            min: Math.floor(min / PRICE_STEP) * PRICE_STEP,
            max: Math.ceil(max / PRICE_STEP) * PRICE_STEP,
        };
        this.resetPrice();
    },

    resetPrice() {
        this.price = { lo: this.bounds.min, hi: this.bounds.max };
    },

    syncPrice() {
        this.filters.min_price = this.price.lo > this.bounds.min ? this.price.lo : null;
        this.filters.max_price = this.price.hi < this.bounds.max ? this.price.hi : null;
    },

    /* ---------- url ---------- */

    readUrl() {
        const q = new URLSearchParams(location.search);
        const f = this.filters;

        f.q = q.get('q') ?? '';
        f.category = q.getAll('category[]');
        f.region = q.getAll('region[]');
        f.in_stock = q.get('in_stock') === '1';
        f.min_rating = ['3', '4'].includes(q.get('min_rating')) ? q.get('min_rating') : '';
        f.sort = this.sorts.some((s) => s.value === q.get('sort')) ? q.get('sort') : 'featured';

        const lo = parseInt(q.get('min_price'), 10);
        const hi = parseInt(q.get('max_price'), 10);
        this.price = {
            lo: Number.isFinite(lo) ? Math.max(lo, this.bounds.min) : this.bounds.min,
            hi: Number.isFinite(hi) ? Math.min(hi, this.bounds.max) : this.bounds.max,
        };
        this.syncPrice();
    },

    syncUrl() {
        const params = new URLSearchParams();
        const f = this.filters;

        if (f.q.trim()) params.set('q', f.q.trim());
        f.category.forEach((v) => params.append('category[]', v));
        f.region.forEach((v) => params.append('region[]', v));
        if (f.min_price !== null) params.set('min_price', f.min_price);
        if (f.max_price !== null) params.set('max_price', f.max_price);
        if (f.in_stock) params.set('in_stock', '1');
        if (f.min_rating) params.set('min_rating', f.min_rating);
        if (f.sort !== 'featured') params.set('sort', f.sort);

        const qs = params.toString();
        history.replaceState(null, '', qs ? `?${qs}` : location.pathname);
    },

    /* ---------- data ---------- */

    query(page) {
        const f = this.filters;
        return {
            q: f.q.trim(),
            category: f.category,
            region: f.region,
            min_price: f.min_price,
            max_price: f.max_price,
            in_stock: f.in_stock,
            min_rating: f.min_rating,
            sort: f.sort,
            page,
            per_page: PER_PAGE,
        };
    },

    schedule() {
        clearTimeout(this.timer);
        this.timer = setTimeout(() => this.fetchFirstPage(), 250);
    },

    async fetchFirstPage() {
        this.more.abort();

        const res = await this.list.load(this.query(1));
        if (!res) return;

        this.items = res.data;
        this.meta = res.meta;
        this.facets = res.facets?.category ?? {};
        this.syncUrl();
    },

    async loadMore() {
        if (!this.hasMore || this.more.loading) return;

        const res = await this.more.load(this.query(this.meta.current_page + 1));
        if (!res) return;

        this.items.push(...res.data);
        this.meta = res.meta;
    },

    retry() {
        this.options.failed ? this.boot() : this.fetchFirstPage();
    },

    clearAll() {
        this.filters = blankFilters();
        this.resetPrice();
    },

    toggle(list, value) {
        const i = this.filters[list].indexOf(value);
        i === -1 ? this.filters[list].push(value) : this.filters[list].splice(i, 1);
    },
});
