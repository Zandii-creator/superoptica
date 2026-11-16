<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Variant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ProductPricingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_it_uses_the_lowest_variant_price_as_reference(): void
    {
        $product = Product::factory()->create(['price_from' => 3000]);

        Variant::factory()->for($product)->create(['price' => 2500]);
        Variant::factory()->for($product)->create(['price' => 1800]);
        Variant::factory()->for($product)->create(['price' => null]);

        $this->assertSame(1800, $product->fresh()->referencePrice());
    }

    public function test_it_falls_back_to_the_product_price_when_variants_have_no_price(): void
    {
        $product = Product::factory()->create(['price_from' => 1500]);

        Variant::factory()->for($product)->create(['price' => null]);

        $this->assertSame(1500, $product->fresh()->referencePrice());
    }

    public function test_it_falls_back_to_the_product_price_without_variants(): void
    {
        $product = Product::factory()->create(['price_from' => 1500]);

        $this->assertSame(1500, $product->fresh()->referencePrice());
    }

    public function test_a_product_without_uploaded_images_has_no_cover(): void
    {
        $product = Product::factory()->create();

        $this->assertNull($product->coverUrl());
    }
}
