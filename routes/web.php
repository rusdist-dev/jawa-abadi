<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.catalog')->name('catalog');

// Hanya slug yang dikirim ke view; seluruh data produk dimuat lewat API.
Route::get('/produk/{slug}', fn (string $slug) => view('pages.product-detail', ['slug' => $slug]))
    ->name('products.show');
