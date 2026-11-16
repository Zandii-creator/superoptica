<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\SiteSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AssetsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_the_fonts_are_self_hosted_and_not_requested_from_third_parties(): void
    {
        $this->assertFileExists(public_path('fonts/inter-latin-var.woff2'));
        $this->assertFileExists(public_path('fonts/space-grotesk-latin-var.woff2'));

        $css = file_get_contents(resource_path('css/app.css'));

        $this->assertStringContainsString('@font-face', $css);
        $this->assertStringContainsString('/fonts/inter-latin-var.woff2', $css);
        $this->assertStringContainsString('/fonts/space-grotesk-latin-var.woff2', $css);
        $this->assertStringNotContainsString('fonts.googleapis.com', $css);
        $this->assertStringNotContainsString('fonts.gstatic.com', $css);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('fonts.googleapis.com', false)
            ->assertDontSee('fonts.gstatic.com', false);
    }

    public function test_the_identity_tokens_are_defined_in_the_stylesheet(): void
    {
        $css = file_get_contents(resource_path('css/app.css'));

        foreach (['--color-navy', '--color-brand', '--color-brand-dark', '--color-brand-soft', '--font-display'] as $token) {
            $this->assertStringContainsString($token, $css);
        }
    }

    public function test_the_favicon_uses_the_20_20_seal_and_is_linked_from_every_page(): void
    {
        $favicon = public_path('favicon.svg');

        $this->assertFileExists($favicon);
        $this->assertStringContainsString('20', file_get_contents($favicon));

        $this->get('/')
            ->assertOk()
            ->assertSee('<link rel="icon" href="/favicon.svg" type="image/svg+xml">', false);

        $this->get('/catalogo')
            ->assertOk()
            ->assertSee('rel="icon" href="/favicon.svg"', false);
    }

    public function test_a_favicon_uploaded_from_the_panel_replaces_the_default_seal(): void
    {
        SiteSettings::current()->update(['favicon' => 'site/favicon-personalizado.png']);

        $this->get('/')
            ->assertOk()
            ->assertSee(
                'href="'.Storage::disk('public')->url('site/favicon-personalizado.png').'"',
                false
            );
    }

    public function test_every_image_has_an_alt_attribute(): void
    {
        Service::factory()->create();

        foreach (['/', '/catalogo', '/servicios', '/tienda'] as $url) {
            $html = $this->get($url)->assertOk()->getContent();

            preg_match_all('/<img\b[^>]*>/i', $html, $matches);

            foreach ($matches[0] as $image) {
                $this->assertMatchesRegularExpression('/\salt="[^"]*"/i', $image, 'Image without alt attribute: '.$image);
            }
        }
    }

    public function test_the_pages_declare_the_language_and_offer_a_skip_link(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('<html lang="es">', false)
            ->assertSee('Saltar al contenido');
    }
}
