<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Product;
use Illuminate\Database\Seeder;

/** Banner iklan sementara; gambar ada di public/images/banners. */
class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            ['Kepiting Bakau Mangrove Super', 'kepiting-bakau.jpg', 'kepiting-bakau-jantan-super'],
            ['Udang Windu Tambak Mangrove Super', 'udang-windu.jpg', 'udang-windu-tambak-mangrove'],
            ['Kakap Putih Bakar Ginda', 'kakap-putih.jpg', 'ikan-kakap-putih'],
            ['Kepiting Soka Goreng Krispi', 'kepiting-soka.jpg', 'kepiting-soka-cangkang-lunak'],
        ];

        foreach ($banners as $i => [$title, $file, $slug]) {
            Banner::updateOrCreate(['image' => '/images/banners/' . $file], [
                'title'      => $title,
                'product_id' => Product::where('slug', $slug)->value('id'),
                'sort_order' => $i,
                'is_active'  => true,
            ]);
        }
    }
}
