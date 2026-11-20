<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\JsonResponse;

class BannerController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $banners = Banner::active()->with('product:id,slug')->get()->map(fn (Banner $b) => [
            'id'    => $b->id,
            'title' => $b->title,
            'image' => $b->image,
            'url'   => $b->product ? route('products.show', $b->product->slug) : $b->link_url,
        ]);

        return response()->json(['data' => $banners]);
    }
}
