<?php

namespace Tests\Feature;

use App\Models\SiteSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SiteNoticesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_the_currency_notice_is_available_for_the_product_page(): void
    {
        $this->assertSame(
            'Precios en moneda nacional (CUP).',
            SiteSettings::current()->currency_notice
        );
    }

    public function test_the_privacy_notice_is_optional(): void
    {
        $this->assertFalse(SiteSettings::current()->hasPrivacyNotice());

        SiteSettings::current()->update(['privacy_notice' => 'No compartimos tus datos.']);

        $this->assertTrue(SiteSettings::current()->hasPrivacyNotice());
        $this->assertSame('No compartimos tus datos.', SiteSettings::current()->privacy_notice);
    }

    public function test_an_unconfigured_social_network_does_not_produce_a_link(): void
    {
        SiteSettings::current()->update([
            'instagram_url' => null,
            'facebook_url' => 'https://facebook.com/superoptica',
        ]);

        $links = collect(SiteSettings::current()->socialLinks());

        $this->assertFalse($links->contains('label', 'Instagram'));
        $this->assertTrue($links->contains('label', 'Facebook'));
    }

    public function test_contact_links_are_null_until_configured(): void
    {
        $this->assertNull(SiteSettings::current()->telegramUrl());
        $this->assertNull(SiteSettings::current()->phoneUrl());

        SiteSettings::current()->update([
            'telegram_username' => 'superoptica2020',
            'phone_number' => '+53 33 123456',
        ]);

        $this->assertSame('https://t.me/superoptica2020', SiteSettings::current()->telegramUrl());
        $this->assertSame('tel:+5333123456', SiteSettings::current()->phoneUrl());
    }

    public function test_the_full_address_does_not_repeat_the_city_when_it_matches_the_province(): void
    {
        SiteSettings::current()->update([
            'address_street' => 'Calle Independencia 45',
            'address_between' => 'Maceo y Frexes',
            'city' => 'Ciego de Ávila',
            'province' => 'Ciego de Ávila',
            'country' => 'Cuba',
        ]);

        $this->assertSame(
            'Calle Independencia 45, entre Maceo y Frexes, Ciego de Ávila, Cuba',
            SiteSettings::current()->fullAddress()
        );
    }
}
