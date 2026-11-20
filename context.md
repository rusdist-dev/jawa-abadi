# Jawa Abadi — Context & Aturan Keras

E-commerce produk hasil kawasan konservasi: bibit mangrove, olahan buah mangrove, kepiting, udang, ikan, lobster, dll.
Referensi gaya visual: `reference.txt` (21st.dev / Hirael `ecommerce-04`) — layout katalog dengan filter sidebar, grid kartu produk 4:5, sort, chip filter aktif, empty state, filter bottom-sheet di mobile.

## Stack

| Lapisan | Pilihan |
|---|---|
| Backend | Laravel 10 (PHP ≥ 8.1) |
| Role & permission | `spatie/laravel-permission` |
| Database | MySQL |
| View | Laravel Blade (server-rendered) |
| Styling | Tailwind CSS 3 (via Vite) |
| Interaksi | Alpine.js |
| Grafik | Chart.js |

## Aturan Keras (WAJIB, tidak boleh dilanggar)

1. **Hanya Laravel Blade, bukan SPA.**
   - Setiap halaman = route web + view Blade. Tidak ada React/Vue/Inertia/Livewire, tidak ada client-side router.
   - Navigasi antar halaman memakai link biasa (`<a href>`), bukan pushState router.
   - Blade hanya membuat kerangka halaman; data dinamis diambil lewat API (lihat aturan 3–4).

2. **Gunakan komponen untuk elemen yang dipakai berulang.**
   - Semua elemen UI yang muncul lebih dari sekali (button, badge, rating, kartu produk, skeleton, empty state, error state, checkbox, switch, section filter, dst.) wajib berupa **Blade component** di `resources/views/components/`.
   - Dilarang menyalin-tempel markup yang sama ke beberapa view. Jika dipakai ≥ 2 kali → jadikan komponen.
   - Komponen anonim (`resources/views/components/**.blade.php`) adalah default; class component hanya bila ada logika PHP.
   - Variasi lewat `@props` (mis. `variant`, `size`) dan `$attributes->merge()`.

3. **Maksimalkan penggunaan API.**
   - Semua data (produk, kategori, kawasan, filter, riwayat harga, dst.) disajikan lewat endpoint `routes/api.php` dan dibaca oleh halaman lewat `fetch`.
   - Controller web **tidak** mengirim data produk ke view; hanya parameter minimum (mis. `slug`).
   - Respons API memakai `JsonResource` / `ResourceCollection`; validasi memakai `FormRequest`.
   - Logika query/filter berada di Model (scope) atau class service — bukan di controller, bukan di JS.
   - Prefix `/api/…`, JSON, status code HTTP yang benar (404 untuk tidak ditemukan, 422 untuk validasi).

4. **Alpine.js memuat data lewat `fetch` dengan load state. Dilarang memakai axios.**
   - Gunakan helper `resources/js/lib/api.js` (wrapper `fetch`) dan `createResource()` untuk state.
   - Setiap pemuatan data punya state eksplisit: `idle | loading | success | error`.
   - UI wajib menangani keempatnya: **skeleton** saat `loading`, **error state + tombol coba lagi** saat `error`, **empty state** bila data kosong.
   - Request yang usang dibatalkan (`AbortController`) agar tidak menimpa hasil terbaru.
   - Tidak ada `axios`, `jQuery.ajax`, atau library HTTP lain di `package.json`.

## Warna

Didefinisikan sebagai token semantik di `tailwind.config.js`; **jangan** memakai nama warna mentah (`teal-600`, `red-500`) langsung di view.

| Token | Palet | Pemakaian |
|---|---|---|
| `primary` | teal | aksi utama, tautan, fokus, slider, checkbox aktif |
| `warning` | amber | stok menipis, rating/bintang, peringatan |
| `danger` | red | stok habis, error, aksi destruktif |
| netral | stone | latar, border, teks sekunder |

## Konvensi

- Bahasa UI: Indonesia. Mata uang: Rupiah (`Intl.NumberFormat('id-ID')`). Harga disimpan sebagai integer rupiah.
- Komponen Blade: `x-ui.*` (primitif), `x-product.*` (domain produk), `x-layout.*` (header/footer).
- JS: ES modules di `resources/js`; Alpine component didaftarkan dengan `Alpine.data()` di `resources/js/pages/*`.
- Aksesibilitas: label pada kontrol, `aria-live` pada jumlah hasil, hormati `prefers-reduced-motion`.
- Role/permission memakai Spatie (`admin`, `staff`); belum ada halaman admin pada tahap ini.

## Struktur

```
app/Http/Controllers/Api/      # ProductController, CatalogFilterController
app/Http/Requests/             # ProductIndexRequest
app/Http/Resources/            # ProductResource, ProductDetailResource
app/Models/                    # Product, Category, Region, PriceHistory
resources/views/layouts/       # app.blade.php
resources/views/pages/         # catalog, product-detail
resources/views/components/    # ui/*, product/*, layout/*
resources/js/lib/api.js        # fetch wrapper + createResource
resources/js/pages/            # catalog.js, product-detail.js
```

## Halaman

- `/` — katalog: filter (kategori, kawasan, harga, stok, rating), pencarian, sort, "muat lebih banyak".
- `/produk/{slug}` — detail produk: galeri, harga, stok, spesifikasi, grafik riwayat harga (Chart.js), produk terkait.
