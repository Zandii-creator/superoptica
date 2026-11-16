<?php

namespace Tests\Feature;

use App\Models\SiteSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SiteSettingsCacheTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_the_settings_are_served_from_cache(): void
    {
        SiteSettings::current();

        DB::table('site_settings')->update(['tagline' => 'Cambiado por fuera del modelo']);

        $this->assertNull(SiteSettings::current()->tagline);
    }

    public function test_saving_invalidates_the_cache(): void
    {
        $settings = SiteSettings::current();

        $settings->update(['tagline' => 'Espejuelos con visión de futuro']);

        $this->assertSame('Espejuelos con visión de futuro', SiteSettings::current()->tagline);
    }

    public function test_updating_through_the_model_keeps_a_single_cached_row(): void
    {
        SiteSettings::current()->update(['tagline' => 'Primero']);
        SiteSettings::current()->update(['tagline' => 'Segundo']);

        $this->assertSame('Segundo', SiteSettings::current()->tagline);
        $this->assertSame(1, SiteSettings::query()->count());
    }
}
