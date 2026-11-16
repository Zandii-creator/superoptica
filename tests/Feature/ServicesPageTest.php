<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\SiteSettings;
use App\Support\WhatsApp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ServicesPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_the_services_page_lists_active_services_in_the_configured_order(): void
    {
        Service::factory()->create(['name' => 'Examen visual', 'sort' => 1]);
        Service::factory()->create(['name' => 'Ajuste de monturas', 'sort' => 2]);
        Service::factory()->create(['name' => 'Servicio oculto', 'sort' => 3, 'is_active' => false]);

        $this->get('/servicios')
            ->assertOk()
            ->assertSeeInOrder(['Examen visual', 'Ajuste de monturas'])
            ->assertDontSee('Servicio oculto');
    }

    public function test_each_service_shows_its_description(): void
    {
        Service::factory()->create([
            'name' => 'Examen visual',
            'description' => 'Revisamos tu visión y te orientamos con calma.',
        ]);

        $this->get('/servicios')
            ->assertOk()
            ->assertSee('Examen visual')
            ->assertSee('Revisamos tu visión y te orientamos con calma.');
    }

    public function test_the_services_page_is_reachable_from_the_main_navigation(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('href="'.route('services.index').'"', false);
    }

    public function test_each_service_offers_a_whatsapp_action_with_its_name(): void
    {
        SiteSettings::current()->update(['whatsapp_number' => '5355123456']);

        $service = Service::factory()->create(['name' => 'Examen visual']);

        $this->get('/servicios')
            ->assertOk()
            ->assertSee(WhatsApp::serviceLink($service), false)
            ->assertSee('Examen visual');
    }

    public function test_the_services_section_shows_no_prices_or_currency_references(): void
    {
        Service::factory()->create();

        $html = $this->get('/servicios')->assertOk()->getContent();

        preg_match('/<main[^>]*>(.*?)<\/main>/s', $html, $matches);
        $main = $matches[1] ?? '';

        $this->assertNotSame('', $main);
        $this->assertStringNotContainsString('CUP', $main);
        $this->assertStringNotContainsString('Desde ', $main);
        $this->assertStringNotContainsString('Precios', $main);
    }

    public function test_the_services_page_has_its_own_metadata_and_canonical(): void
    {
        $this->get('/servicios')
            ->assertOk()
            ->assertSee('<link rel="canonical" href="'.route('services.index').'">', false)
            ->assertSee('Servicios ópticos en Ciego de Ávila');
    }
}
