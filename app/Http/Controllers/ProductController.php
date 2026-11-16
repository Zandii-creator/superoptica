<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SiteSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function show(Product $product): View
    {
        abort_unless($product->isPubliclyVisible(), 404);

        $product->load(['brand', 'material', 'shape', 'variants']);

        $settings = SiteSettings::current();
        $image = $product->coverUrl();

        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->displayName(),
            'sku' => $product->model,
            'url' => route('products.show', $product),
            'description' => $product->short_description
                ?: Str::limit(trim(strip_tags((string) $product->description)), 200),
            'brand' => [
                '@type' => 'Brand',
                'name' => $product->brand?->name,
            ],
            'offers' => [
                '@type' => 'Offer',
                'price' => $product->referencePrice(),
                'priceCurrency' => $settings->currency_label ?: 'CUP',
                'availability' => 'https://schema.org/InStock',
                'url' => route('products.show', $product),
            ],
        ];

        if ($image !== null) {
            $structuredData['image'] = url($image);
        }

        return view('products.show', [
            'product' => $product,
            'settings' => $settings,
            'title' => $product->displayName(),
            'description' => $product->short_description
                ?: Str::limit(trim(strip_tags((string) $product->description)), 155),
            'canonical' => route('products.show', $product),
            'jsonLd' => $structuredData,
        ]);
    }
}
