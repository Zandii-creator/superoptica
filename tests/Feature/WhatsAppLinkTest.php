<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Service;
use App\Models\SiteSettings;
use App\Models\Variant;
use App\Support\WhatsApp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class WhatsAppLinkTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();

        SiteSettings::current()->update(['whatsapp_number' => '5355123456']);
    }

    public function test_it_builds_a_wa_me_link_with_an_encoded_message(): void
    {
        $link = WhatsApp::link('Hola, quiero información');

        $this->assertSame(
            'https://wa.me/5355123456?text='.rawurlencode('Hola, quiero información'),
            $link
        );
    }

    public function test_the_product_link_mentions_the_model_and_the_selected_color(): void
    {
        $brand = Brand::factory()->create(['name' => 'Ray-Ban']);
        $product = Product::factory()->for($brand)->create(['model' => '82278']);
        $variant = Variant::factory()->for($product)->create(['color_name' => 'Negro']);

        $message = urldecode(parse_url(WhatsApp::productLink($product, $variant), PHP_URL_QUERY));

        $this->assertStringContainsString('Ray-Ban', $message);
        $this->assertStringContainsString('82278', $message);
        $this->assertStringContainsString('Negro', $message);
    }

    public function test_the_product_link_works_without_a_selected_color(): void
    {
        $product = Product::factory()->create(['model' => '82278']);

        $message = urldecode(parse_url(WhatsApp::productLink($product), PHP_URL_QUERY));

        $this->assertStringContainsString('82278', $message);
    }

    public function test_the_service_link_mentions_the_service_name(): void
    {
        $service = Service::factory()->create(['name' => 'Examen visual']);

        $message = urldecode(parse_url(WhatsApp::serviceLink($service), PHP_URL_QUERY));

        $this->assertStringContainsString('Examen visual', $message);
    }

    public function test_it_ignores_formatting_characters_in_the_configured_number(): void
    {
        SiteSettings::current()->update(['whatsapp_number' => '+53 55 123456']);

        $this->assertStringStartsWith('https://wa.me/5355123456?text=', WhatsApp::link('Hola'));
    }
}
