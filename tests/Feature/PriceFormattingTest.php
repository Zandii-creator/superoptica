<?php

namespace Tests\Feature;

use App\Models\SiteSettings;
use App\Support\Price;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PriceFormattingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_it_formats_cup_amounts_with_thousands_separator_and_no_decimals(): void
    {
        $this->assertSame('1,500 CUP', Price::format(1500));
        $this->assertSame('850 CUP', Price::format(850));
        $this->assertSame('12,000 CUP', Price::format(12000));
        $this->assertSame('0 CUP', Price::format(0));
    }

    public function test_it_prefixes_the_reference_price_with_desde(): void
    {
        $this->assertSame('Desde 1,500 CUP', Price::from(1500));
    }

    public function test_it_uses_the_currency_label_configured_by_the_business(): void
    {
        SiteSettings::current()->update(['currency_label' => 'CUP']);

        $this->assertSame('1,500 CUP', Price::format(1500));
    }

    public function test_it_never_uses_the_dollar_symbol(): void
    {
        $this->assertStringNotContainsString('$', Price::from(1500));
    }
}
