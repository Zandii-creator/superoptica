<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ProductPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_a_published_product_shows_its_data_price_and_national_currency_notice(): void
    {
        $brand = Brand::factory()->create(['name' => 'Ray-Ban']);
        $product = Product::factory()->for($brand)->create([
            'model' => '82278',
            'slug' => 'modelo-82278',
            'price_from' => 1500,
        ]);

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('Ray-Ban')
            ->assertSee('82278')
            ->assertSee('Desde 1,500 CUP')
            ->assertSee('Precios en moneda nacional (CUP).');
    }

    public function test_the_product_page_has_unique_metadata_and_structured_data(): void
    {
        $brand = Brand::factory()->create(['name' => 'Ray-Ban']);
        $product = Product::factory()->for($brand)->create([
            'model' => '82278',
            'slug' => 'modelo-82278',
        ]);

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('<link rel="canonical" href="'.route('products.show', $product).'">', false)
            ->assertSee('"@type":"Product"', false)
            ->assertSee('"priceCurrency":"CUP"', false);
    }

    public function test_the_reference_price_uses_the_lowest_variant(): void
    {
        $product = Product::factory()->create([
            'model' => '82278',
            'slug' => 'modelo-82278',
            'price_from' => 3000,
        ]);

        Variant::factory()->for($product)->create(['price' => 2500]);
        Variant::factory()->for($product)->create(['price' => 1800]);

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('Desde 1,800 CUP');
    }

    public function test_a_draft_product_is_not_accessible(): void
    {
        $product = Product::factory()->create([
            'slug' => 'modelo-borrador',
            'published_at' => null,
        ]);

        $this->get(route('products.show', $product))->assertNotFound();
    }
}
