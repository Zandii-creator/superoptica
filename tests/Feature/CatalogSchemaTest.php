<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Service;
use App\Models\Variant;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CatalogSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_product_is_active_and_not_featured_by_default(): void
    {
        $product = Product::factory()->create();

        $this->assertTrue($product->fresh()->is_active);
        $this->assertFalse($product->fresh()->is_featured);
    }

    public function test_a_brand_is_active_by_default(): void
    {
        $this->assertTrue(Brand::factory()->create()->fresh()->is_active);
    }

    public function test_a_variant_is_sorted_first_by_default(): void
    {
        $this->assertSame(0, Variant::factory()->create()->fresh()->sort);
    }

    public function test_a_service_is_active_and_sorted_first_by_default(): void
    {
        $service = Service::factory()->create()->fresh();

        $this->assertTrue($service->is_active);
        $this->assertSame(0, $service->sort);
    }

    public function test_site_settings_row_has_typed_defaults(): void
    {
        DB::table('site_settings')->insert([
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $settings = DB::table('site_settings')->first();

        $this->assertSame('CUP', $settings->currency_label);
        $this->assertSame('SuperÓptica 20 20', $settings->site_name);
        $this->assertNull($settings->instagram_url);
    }

    public function test_product_slug_is_unique(): void
    {
        Product::factory()->create(['slug' => 'modelo-82278']);

        $this->expectException(QueryException::class);

        Product::factory()->create(['slug' => 'modelo-82278']);
    }

    public function test_catalog_tables_have_their_filter_indexes(): void
    {
        $productIndexes = collect(Schema::getIndexes('products'));

        $this->assertTrue(
            $productIndexes->contains(fn (array $index) => $index['columns'] === ['slug'] && $index['unique']),
            'La columna slug de products debe ser unica.'
        );

        $this->assertTrue(
            $productIndexes->contains(fn (array $index) => $index['columns'] === ['is_active', 'published_at']),
            'products debe tener un indice compuesto por is_active y published_at.'
        );

        $variantIndexes = collect(Schema::getIndexes('variants'));
        $this->assertTrue(
            $variantIndexes->contains(fn (array $index) => $index['columns'] === ['product_id']),
            'variants debe tener indice por product_id.'
        );
    }
}
