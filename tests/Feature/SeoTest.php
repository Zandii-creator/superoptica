<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Product;
use App\Models\SiteSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_the_home_page_has_its_own_metadata_and_local_business_data(): void
    {
        SiteSettings::current()->update([
            'city' => 'Ciego de Ávila',
            'province' => 'Ciego de Ávila',
            'country' => 'Cuba',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('<title>', false)
            ->assertSee('Ciego de Ávila')
            ->assertSee('application/ld+json', false)
            ->assertSee('"@type":"Optician"', false);
    }

    public function test_the_catalog_canonical_ignores_the_pagination_parameter(): void
    {
        Product::factory()->count(13)->create();

        $this->get('/catalogo?page=2')
            ->assertOk()
            ->assertSee('<link rel="canonical" href="'.url('/catalogo').'">', false);
    }

    public function test_the_catalog_canonical_keeps_the_active_filters(): void
    {
        Brand::factory()->create(['slug' => 'ray-ban']);

        $this->get('/catalogo?marca=ray-ban')
            ->assertOk()
            ->assertSee(
                '<link rel="canonical" href="'.url('/catalogo').'?marca=ray-ban">',
                false
            );
    }

    public function test_the_sitemap_lists_the_public_pages_and_published_products(): void
    {
        $published = Product::factory()->create(['slug' => 'modelo-82278']);
        Product::factory()->create(['slug' => 'modelo-borrador', 'published_at' => null]);

        $response = $this->get('/sitemap.xml');

        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee('<loc>'.url('/').'</loc>', false)
            ->assertSee('<loc>'.url('/catalogo').'</loc>', false)
            ->assertSee('<loc>'.route('products.show', $published).'</loc>', false)
            ->assertDontSee('modelo-borrador');
    }
}
