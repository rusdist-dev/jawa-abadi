<?php

use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\CatalogFilterController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/banners', BannerController::class)->name('api.banners');
Route::get('/catalog/filters', CatalogFilterController::class)->name('api.catalog.filters');

Route::get('/products', [ProductController::class, 'index'])->name('api.products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('api.products.show');
Route::get('/products/{slug}/related', [ProductController::class, 'related'])->name('api.products.related');
Route::get('/products/{slug}/price-history', [ProductController::class, 'priceHistory'])->name('api.products.price-history');
