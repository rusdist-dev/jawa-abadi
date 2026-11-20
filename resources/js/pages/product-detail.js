import { api, createResource, rupiah } from '../lib/api';

// Instance Chart.js disimpan di luar state Alpine agar tidak dibungkus proxy reaktif.
let chart = null;

export default (slug, whatsapp = '') => ({
    slug,
    product: createResource((opts) => api(`/products/${slug}`, opts).then((r) => r.data)),
    related: createResource((opts) => api(`/products/${slug}/related`, opts).then((r) => r.data)),
    history: createResource((opts) => api(`/products/${slug}/price-history`, opts).then((r) => r.data)),

    imageIndex: 0,
    qty: 1,

    init() {
        this.loadAll();
    },

    async loadAll() {
        // Tiga permintaan berjalan paralel; halaman hanya bergantung pada `product`.
        const productReq = this.product.load();
        this.related.load();
        this.history.load().then((points) => points && this.drawChart(points));

        const product = await productReq;
        if (!product) return;

        document.title = `${product.name} — ${document.title.split('—').pop().trim()}`;
        this.qty = this.minQty;
    },

    retry() {
        this.loadAll();
    },

    /* ---------- derived ---------- */

    get p() {
        return this.product.data;
    },
    get notFound() {
        return this.product.failed && this.product.error?.status === 404;
    },
    get minQty() {
        return this.p?.min_order ?? 1;
    },
    get maxQty() {
        return this.p?.stock ?? 1;
    },
    get canOrder() {
        return !!this.p?.in_stock && this.maxQty >= this.minQty;
    },
    get total() {
        return (this.p?.price ?? 0) * this.qty;
    },
    get images() {
        return this.p?.images ?? [];
    },
    get currentImage() {
        return this.images[this.imageIndex] ?? null;
    },

    get stats() {
        const points = this.history.data ?? [];
        if (points.length < 2) return null;

        const prices = points.map((x) => x.price);
        const first = prices[0];
        const last = prices[prices.length - 1];

        return {
            min: Math.min(...prices),
            max: Math.max(...prices),
            change: first ? ((last - first) / first) * 100 : 0,
        };
    },

    get orderUrl() {
        if (!this.p) return '#';

        const text = [
            `Halo, saya ingin memesan:`,
            `${this.p.name}`,
            `Jumlah: ${this.qty} ${this.p.unit}`,
            `Perkiraan total: ${rupiah(this.total)}`,
        ].join('\n');

        return `https://wa.me/${whatsapp}?text=${encodeURIComponent(text)}`;
    },

    /* ---------- actions ---------- */

    step(delta) {
        this.setQty(this.qty + delta);
    },

    setQty(value) {
        const n = Math.round(Number(value));
        this.qty = Math.min(Math.max(Number.isFinite(n) ? n : this.minQty, this.minQty), this.maxQty);
    },

    /* ---------- chart ---------- */

    async drawChart(points) {
        const { default: Chart } = await import('chart.js/auto');
        await this.$nextTick();

        const canvas = this.$refs.chart;
        if (!canvas) return;

        chart?.destroy();

        const styles = getComputedStyle(document.documentElement);
        const color = styles.getPropertyValue('--chart-line').trim() || '#0d9488';
        const fill = styles.getPropertyValue('--chart-fill').trim() || 'rgba(13, 148, 136, 0.12)';

        const dateLabel = (iso) =>
            new Date(iso).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });

        chart = new Chart(canvas, {
            type: 'line',
            data: {
                labels: points.map((x) => x.date),
                datasets: [
                    {
                        label: 'Harga',
                        data: points.map((x) => x.price),
                        borderColor: color,
                        backgroundColor: fill,
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                        pointRadius: 0,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: color,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        displayColors: false,
                        callbacks: {
                            title: (items) => dateLabel(items[0].label),
                            label: (item) => rupiah(item.parsed.y),
                        },
                    },
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { maxTicksLimit: 6, callback: (_, i) => dateLabel(points[i].date) },
                    },
                    y: {
                        ticks: { maxTicksLimit: 5, callback: (v) => `${Math.round(v / 1000)}rb` },
                    },
                },
            },
        });
    },
});
