<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\PriceHistory;
use App\Models\Product;
use App\Models\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Data contoh (demo) untuk pengembangan. Ganti dengan data asli sebelum produksi.
 */
class ShopSeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect([
            ['Bibit Mangrove', 'Bibit siap tanam untuk rehabilitasi pesisir.'],
            ['Olahan Mangrove', 'Pangan olahan dari buah dan daun mangrove.'],
            ['Kepiting', 'Kepiting bakau hasil tambak silvofishery.'],
            ['Udang', 'Udang windu dan vaname dari tambak ramah mangrove.'],
            ['Ikan', 'Ikan tangkap dan budidaya kawasan konservasi.'],
            ['Lobster', 'Lobster hasil budidaya keramba laut.'],
        ])->mapWithKeys(function ($row, $i) {
            $c = Category::updateOrCreate(['slug' => Str::slug($row[0])], [
                'name' => $row[0], 'description' => $row[1], 'sort_order' => $i,
            ]);

            return [$c->slug => $c->id];
        });

        $regions = collect([
            ['Mangrove Wonorejo', 'Surabaya, Jawa Timur'],
            ['Segara Anakan', 'Cilacap, Jawa Tengah'],
            ['Karimunjawa', 'Jepara, Jawa Tengah'],
            ['Pantai Indah Kapuk', 'Jakarta Utara, DKI Jakarta'],
        ])->mapWithKeys(function ($row) {
            $r = Region::updateOrCreate(['slug' => Str::slug($row[0])], [
                'name' => $row[0], 'location' => $row[1],
            ]);

            return [$r->slug => $r->id];
        });

        // [nama, kategori, kawasan, harga, satuan, min, stok, rating, ulasan, unggulan, ringkasan, spesifikasi]
        $items = [
            ['Bibit Rhizophora mucronata', 'bibit-mangrove', 'mangrove-wonorejo', 4500, 'bibit', 50, 1200, 4.8, 214, true,
                'Bibit bakau minyak umur 4 bulan, tinggi 40–50 cm, siap tanam.',
                ['Umur bibit' => '4 bulan', 'Tinggi' => '40–50 cm', 'Media' => 'Polybag tanah lumpur']],
            ['Bibit Avicennia marina (api-api)', 'bibit-mangrove', 'segara-anakan', 3800, 'bibit', 50, 800, 4.6, 133, false,
                'Bibit api-api toleran salinitas tinggi, cocok untuk garis pantai terbuka.',
                ['Umur bibit' => '3 bulan', 'Tinggi' => '30–40 cm', 'Media' => 'Polybag']],
            ['Bibit Bruguiera gymnorrhiza', 'bibit-mangrove', 'segara-anakan', 5200, 'bibit', 25, 420, 4.7, 88, false,
                'Bibit tancang dengan perakaran kuat untuk zona tengah mangrove.',
                ['Umur bibit' => '5 bulan', 'Tinggi' => '45–60 cm']],
            ['Bibit Sonneratia alba (pedada)', 'bibit-mangrove', 'karimunjawa', 6000, 'bibit', 25, 8, 4.4, 41, false,
                'Bibit pedada untuk zona depan berlumpur; stok terbatas.',
                ['Umur bibit' => '4 bulan', 'Tinggi' => '35–45 cm']],
            ['Sirup Buah Pedada', 'olahan-mangrove', 'mangrove-wonorejo', 38000, 'botol', 1, 150, 4.9, 502, true,
                'Sirup dari buah pedada, rasa asam segar tanpa pengawet.',
                ['Volume' => '460 ml', 'Bahan' => 'Buah pedada, gula', 'Daya simpan' => '12 bulan']],
            ['Tepung Mangrove Lindur', 'olahan-mangrove', 'segara-anakan', 42000, 'kg', 1, 90, 4.5, 176, false,
                'Tepung dari buah lindur (Bruguiera), bebas gluten.',
                ['Berat bersih' => '1 kg', 'Bahan' => '100% buah lindur', 'Daya simpan' => '6 bulan']],
            ['Dodol Buah Api-api', 'olahan-mangrove', 'mangrove-wonorejo', 27000, 'bungkus', 1, 6, 4.3, 64, false,
                'Dodol tradisional dari buah api-api, dikelola kelompok perempuan pesisir.',
                ['Berat bersih' => '250 g', 'Daya simpan' => '2 bulan']],
            ['Keripik Daun Jeruju', 'olahan-mangrove', 'pantai-indah-kapuk', 22000, 'bungkus', 1, 210, 4.2, 97, false,
                'Keripik renyah dari daun jeruju (Acanthus), gurih.',
                ['Berat bersih' => '150 g']],
            ['Kepiting Bakau Jantan Super', 'kepiting', 'segara-anakan', 185000, 'kg', 1, 60, 4.8, 389, true,
                'Kepiting bakau jantan, isi 2–3 ekor per kg, dikirim hidup.',
                ['Ukuran' => '2–3 ekor/kg', 'Kondisi' => 'Hidup', 'Budidaya' => 'Silvofishery']],
            ['Kepiting Soka (Cangkang Lunak)', 'kepiting', 'mangrove-wonorejo', 145000, 'kg', 1, 0, 4.6, 120, false,
                'Kepiting soka beku, siap goreng tepung.',
                ['Ukuran' => '4–5 ekor/kg', 'Kondisi' => 'Beku']],
            ['Udang Windu Tambak Mangrove', 'udang', 'segara-anakan', 135000, 'kg', 1, 75, 4.7, 245, true,
                'Udang windu size 30, dari tambak tanpa pestisida.',
                ['Ukuran' => 'Size 30', 'Kondisi' => 'Segar dingin']],
            ['Udang Vaname Size 50', 'udang', 'pantai-indah-kapuk', 78000, 'kg', 2, 180, 4.4, 301, false,
                'Udang vaname segar, cocok untuk kebutuhan rumah tangga dan katering.',
                ['Ukuran' => 'Size 50', 'Kondisi' => 'Segar dingin']],
            ['Ikan Kakap Putih', 'ikan', 'karimunjawa', 98000, 'kg', 1, 40, 4.6, 158, false,
                'Kakap putih budidaya keramba, daging tebal dan manis.',
                ['Ukuran' => '600–800 g/ekor', 'Kondisi' => 'Segar dingin']],
            ['Ikan Bandeng Tambak', 'ikan', 'mangrove-wonorejo', 42000, 'kg', 2, 300, 4.3, 412, false,
                'Bandeng tambak organik, ukuran konsumsi.',
                ['Ukuran' => '3–4 ekor/kg', 'Kondisi' => 'Segar']],
            ['Ikan Kerapu Macan', 'ikan', 'karimunjawa', 165000, 'kg', 1, 22, 4.9, 77, true,
                'Kerapu macan hidup dari keramba jaring apung Karimunjawa.',
                ['Ukuran' => '500–700 g/ekor', 'Kondisi' => 'Hidup']],
            ['Ikan Nila Salin', 'ikan', 'pantai-indah-kapuk', 35000, 'kg', 2, 5, 3.9, 29, false,
                'Nila salin air payau, daging bersih tanpa bau lumpur.',
                ['Ukuran' => '3–4 ekor/kg', 'Kondisi' => 'Segar']],
            ['Lobster Pasir Hidup', 'lobster', 'karimunjawa', 520000, 'kg', 1, 18, 4.9, 143, true,
                'Lobster pasir (Panulirus homarus) hidup, isi 3–4 ekor per kg.',
                ['Ukuran' => '3–4 ekor/kg', 'Kondisi' => 'Hidup', 'Asal' => 'Keramba laut']],
            ['Lobster Mutiara Beku', 'lobster', 'karimunjawa', 640000, 'kg', 1, 0, 4.8, 61, false,
                'Lobster mutiara premium, dibekukan segera setelah panen.',
                ['Ukuran' => '2–3 ekor/kg', 'Kondisi' => 'Beku']],
            ['Paket Bibit Mangrove 100', 'bibit-mangrove', 'mangrove-wonorejo', 399000, 'paket', 1, 35, 4.9, 56, false,
                'Paket 100 bibit campuran (Rhizophora & Avicennia) untuk aksi tanam komunitas.',
                ['Isi paket' => '100 bibit campuran', 'Panduan tanam' => 'Termasuk']],
            ['Teh Daun Mangrove', 'olahan-mangrove', 'segara-anakan', 32000, 'kotak', 1, 130, 4.1, 45, false,
                'Teh herbal daun api-api, isi 20 kantong celup.',
                ['Isi' => '20 kantong', 'Bahan' => 'Daun api-api kering']],
        ];

        $today = Carbon::today();

        foreach ($items as $n => $i) {
            [$name, $cat, $reg, $price, $unit, $min, $stock, $rating, $reviews, $featured, $summary, $specs] = $i;

            $product = Product::updateOrCreate(['slug' => Str::slug($name)], [
                'category_id'   => $categories[$cat],
                'region_id'     => $regions[$reg],
                'name'          => $name,
                'summary'       => $summary,
                'description'   => $summary . "\n\nDiproduksi oleh masyarakat sekitar kawasan konservasi. Setiap pembelian ikut mendukung pelestarian ekosistem pesisir dan penghidupan nelayan serta petambak lokal.\n\nPesanan dikemas sesuai standar penanganan produk dan dikirim sesuai jadwal panen.",
                'price'         => $price,
                'unit'          => $unit,
                'min_order'     => $min,
                'stock'         => $stock,
                'rating'        => $rating,
                'reviews_count' => $reviews,
                // Gambar sementara dari public/images/products/{slug}.jpg (jika ada).
                'images'        => file_exists(public_path('images/products/' . Str::slug($name) . '.jpg'))
                    ? ['/images/products/' . Str::slug($name) . '.jpg']
                    : null,
                'specs'         => $specs,
                'is_featured'   => $featured,
                'is_active'     => true,
                'created_at'    => $today->copy()->subDays(($n * 9) % 120),
            ]);

            // Riwayat harga 6 bulan terakhir (mingguan), fluktuasi deterministik.
            for ($w = 25; $w >= 0; $w--) {
                $wave = sin(($w + $n) / 3) * 0.06 + (($w * 7 + $n * 13) % 5 - 2) * 0.01;
                $point = $w === 0 ? $price : (int) (round($price * (1 + $wave) / 500) * 500);

                PriceHistory::updateOrCreate(
                    ['product_id' => $product->id, 'recorded_on' => $today->copy()->subWeeks($w)->toDateString()],
                    ['price' => max($point, 500)],
                );
            }
        }
    }
}
