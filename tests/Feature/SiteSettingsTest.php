<?php

namespace Tests\Feature;

use App\Models\SiteSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_default_values_when_nothing_is_configured(): void
    {
        $settings = SiteSettings::current();

        $this->assertSame('SuperÓptica 20 20', $settings->site_name);
        $this->assertSame('CUP', $settings->currency_label);
        $this->assertSame('Precios en moneda nacional (CUP).', $settings->currency_notice);
        $this->assertNull($settings->whatsapp_number);
    }

    public function test_it_always_uses_a_single_row(): void
    {
        $first = SiteSettings::current();
        $second = SiteSettings::current();

        $this->assertSame($first->id, $second->id);
        $this->assertSame(1, SiteSettings::query()->count());
    }

    public function test_it_persists_changes_in_the_same_row(): void
    {
        $settings = SiteSettings::current();
        $settings->update([
            'tagline' => 'Espejuelos con visión de futuro',
            'whatsapp_number' => '5355123456',
        ]);

        $this->assertSame(1, SiteSettings::query()->count());
        $this->assertSame('Espejuelos con visión de futuro', SiteSettings::current()->tagline);
        $this->assertSame('5355123456', SiteSettings::current()->whatsapp_number);
    }

    public function test_it_exposes_the_weekly_hours_with_closed_days_as_null(): void
    {
        $settings = SiteSettings::current();
        $settings->update([
            'hours_monday' => '9:00 - 17:00',
            'hours_sunday' => null,
        ]);

        $hours = SiteSettings::current()->weeklyHours();

        $this->assertSame('Lunes', $hours[0]['day']);
        $this->assertSame('9:00 - 17:00', $hours[0]['hours']);
        $this->assertSame('Domingo', $hours[6]['day']);
        $this->assertNull($hours[6]['hours']);
    }
}
