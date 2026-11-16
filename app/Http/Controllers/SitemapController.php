<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        return response()->view('sitemap', [
            'products' => Product::query()
                ->published()
                ->orderByDesc('published_at')
                ->get(['slug', 'updated_at']),
        ], 200, ['Content-Type' => 'application/xml']);
    }
}
