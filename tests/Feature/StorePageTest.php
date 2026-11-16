<?php

namespace Tests\Feature;

use App\Models\SiteSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class StorePageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_the_store_page_shows_the_full_address_and_the_between_streets_reference(): void
    {
        SiteSettings::current()->update([
            'address_street' => 'Calle Independencia 45',
            'address_between' => 'Maceo y Frexes',
            'city' => 'Ciego de Ávila',
            'province' => 'Ciego de Ávila',
            'country' => 'Cuba',
        ]);

        $this->get('/tienda')
            ->assertOk()
            ->assertSee('Calle Independencia 45')
            ->assertSee('entre Maceo y Frexes')
            ->assertSee('Ciego de Ávila')
            ->assertSee('Cuba');
    }

    public function test_the_store_page_shows_every_weekday_and_marks_closed_days(): void
    {
        SiteSettings::current()->update([
            'hours_monday' => '9:00 - 17:00',
            'hours_tuesday' => '9:00 - 17:00',
            'hours_sunday' => null,
        ]);

        $this->get('/tienda')
            ->assertOk()
            ->assertSeeInOrder(['Lunes', '9:00 - 17:00', 'Martes', '9:00 - 17:00'])
            ->assertSee('Domingo')
            ->assertSee('Cerrado');
    }

    public function test_the_store_page_does_not_load_third_party_maps_on_the_initial_request(): void
    {
        $html = $this->get('/tienda')->assertOk()->getContent();

        $this->assertStringNotContainsString('<iframe', $html);

        foreach (['maps.googleapis.com', 'google.com/maps/embed', 'openstreetmap.org/export', 'mapbox.com', 'bing.com/maps'] as $host) {
            $this->assertStringNotContainsString($host, $html);
        }
    }

    public function test_the_store_page_shows_a_static_map_reference_and_an_external_link(): void
    {
        SiteSettings::current()->update([
            'map_url' => 'https://maps.google.com/?q=Ciego+de+Avila',
        ]);

        $this->get('/tienda')
            ->assertOk()
            ->assertSee('aria-label="Referencia de ubicación de la tienda"', false)
            ->assertSee('Abrir en el mapa')
            ->assertSee('https://maps.google.com/?q=Ciego+de+Avila', false);
    }

    public function test_the_contact_channels_are_actionable_links(): void
    {
        SiteSettings::current()->update([
            'whatsapp_number' => '5355123456',
            'phone_number' => '+53 33 123456',
            'telegram_username' => 'superoptica2020',
        ]);

        $this->get('/tienda')
            ->assertOk()
            ->assertSee('https://wa.me/5355123456', false)
            ->assertSee('href="tel:+5333123456"', false)
            ->assertSee('href="https://t.me/superoptica2020"', false);
    }

    public function test_the_floating_whatsapp_button_is_available_on_every_public_page(): void
    {
        SiteSettings::current()->update(['whatsapp_number' => '5355123456']);

        foreach (['/', '/catalogo', '/servicios', '/tienda'] as $url) {
            $this->get($url)
                ->assertOk()
                ->assertSee('aria-label="Escríbenos por WhatsApp"', false);
        }
    }

    public function test_the_floating_whatsapp_button_is_not_rendered_without_a_number(): void
    {
        SiteSettings::current()->update(['whatsapp_number' => null]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('aria-label="Escríbenos por WhatsApp"', false);
    }

    public function test_the_store_page_has_its_own_metadata_and_canonical(): void
    {
        $this->get('/tienda')
            ->assertOk()
            ->assertSee('<link rel="canonical" href="'.route('store.index').'">', false)
            ->assertSee('Tienda y contacto en Ciego de Ávila');
    }
}
