<?php

namespace Tests\Feature\Filament;

use App\Filament\Pages\ManageSiteSettings;
use App\Models\SiteSettings;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SiteSettingsPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_the_administrator_saves_the_site_settings(): void
    {
        Livewire::test(ManageSiteSettings::class)
            ->fillForm([
                'tagline' => 'Espejuelos con visión de futuro',
                'address_street' => 'Calle Independencia 45',
                'whatsapp_number' => '5355123456',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $settings = SiteSettings::current();

        $this->assertSame('Espejuelos con visión de futuro', $settings->tagline);
        $this->assertSame('Calle Independencia 45', $settings->address_street);
        $this->assertSame('5355123456', $settings->whatsapp_number);
    }

    public function test_it_rejects_an_invalid_whatsapp_number(): void
    {
        Livewire::test(ManageSiteSettings::class)
            ->fillForm([
                'address_street' => 'Calle Independencia 45',
                'whatsapp_number' => 'llamame',
            ])
            ->call('save')
            ->assertHasFormErrors(['whatsapp_number']);

        $this->assertNull(SiteSettings::current()->whatsapp_number);
    }

    public function test_it_rejects_a_missing_store_address(): void
    {
        Livewire::test(ManageSiteSettings::class)
            ->fillForm([
                'address_street' => '',
                'whatsapp_number' => '5355123456',
            ])
            ->call('save')
            ->assertHasFormErrors(['address_street' => 'required']);

        $this->assertNull(SiteSettings::current()->address_street);
    }

    public function test_it_rejects_a_malformed_instagram_link(): void
    {
        Livewire::test(ManageSiteSettings::class)
            ->fillForm([
                'address_street' => 'Calle Independencia 45',
                'whatsapp_number' => '5355123456',
                'instagram_url' => 'instagram/20 20',
            ])
            ->call('save')
            ->assertHasFormErrors(['instagram_url']);

        $this->assertNull(SiteSettings::current()->instagram_url);
    }

    public function test_the_page_is_only_available_to_authenticated_administrators(): void
    {
        auth()->logout();

        $this->get('/admin/ajustes')->assertRedirect('/admin/login');
    }
}
