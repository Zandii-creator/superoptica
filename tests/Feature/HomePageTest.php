<?php

namespace Tests\Feature;

use App\Models\SiteSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_the_home_page_renders_the_brand_and_the_catalog_call_to_action(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('20 20')
            ->assertSee('Ver el catálogo')
            ->assertSee(route('catalog.index'), false);
    }

    public function test_the_home_page_uses_the_hero_texts_configured_in_the_panel(): void
    {
        SiteSettings::current()->update([
            'hero_title' => 'Tu visión merece lo mejor',
            'hero_subtitle' => 'Monturas y lentes de contacto en el centro de la ciudad.',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Tu visión merece lo mejor')
            ->assertSee('Monturas y lentes de contacto en el centro de la ciudad.');
    }
}
