<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Service;
use App\Models\SiteSettings;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $settings = SiteSettings::current();

        $featured = Product::query()
            ->featured()
            ->with(['brand', 'variants'])
            ->take(3)
            ->get();

        $services = Service::query()
            ->active()
            ->ordered()
            ->get();

        $structuredData = array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Optician',
            'name' => $settings->site_name,
            'description' => $settings->seo_description
                ?: 'Espejuelos, monturas, lentes de contacto y líquidos en Ciego de Ávila.',
            'url' => route('home'),
            'telephone' => $settings->phone_number,
            'address' => array_filter([
                '@type' => 'PostalAddress',
                'streetAddress' => $settings->address_street,
                'addressLocality' => $settings->city,
                'addressRegion' => $settings->province,
                'addressCountry' => $settings->country,
            ]),
        ], static fn (mixed $value): bool => $value !== null && $value !== '');

        return view('home', [
            'settings' => $settings,
            'featured' => $featured,
            'services' => $services,
            'title' => $settings->seo_title ?: null,
            'description' => $settings->seo_description
                ?: 'Espejuelos, monturas, lentes de contacto y líquidos en Ciego de Ávila.',
            'canonical' => route('home'),
            'jsonLd' => $structuredData,
        ]);
    }
}
