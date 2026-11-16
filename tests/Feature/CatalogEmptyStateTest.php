<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\SiteSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CatalogEmptyStateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_an_empty_catalog_explains_that_there_are_no_published_products(): void
    {
        $this->get('/catalogo')
            ->assertOk()
            ->assertSee('Todavía no hay productos publicados');
    }

    public function test_a_search_without_results_offers_to_clear_the_filters(): void
    {
        Product::factory()->create(['model' => '11111']);

        $this->get('/catalogo?q=99999')
            ->assertOk()
            ->assertSee('No encontramos espejuelos con esos filtros')
            ->assertSee('Limpiar filtros');
    }

    public function test_the_empty_state_offers_a_contact_channel_when_whatsapp_is_configured(): void
    {
        SiteSettings::current()->update(['whatsapp_number' => '5355123456']);

        $this->get('/catalogo')
            ->assertOk()
            ->assertSee('https://wa.me/5355123456', false);
    }

    public function test_the_empty_state_does_not_render_a_broken_whatsapp_link_without_a_number(): void
    {
        $this->get('/catalogo')
            ->assertOk()
            ->assertDontSee('https://wa.me/?', false);
    }
}
