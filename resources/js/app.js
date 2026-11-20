import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';

import { number, rupiah } from './lib/api';
import bannerCarousel from './pages/banner-carousel';
import catalog from './pages/catalog';
import productDetail from './pages/product-detail';

Alpine.plugin(focus);

Alpine.magic('rupiah', () => rupiah);
Alpine.magic('number', () => number);

Alpine.data('bannerCarousel', bannerCarousel);
Alpine.data('catalog', catalog);
Alpine.data('productDetail', productDetail);

window.Alpine = Alpine;
Alpine.start();
