<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Material;
use App\Models\Product;
use App\Models\Shape;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_the_product_page_shows_all_the_available_information(): void
    {
        $brand = Brand::factory()->create(['name' => 'Ray-Ban']);
        $material = Material::factory()->create(['name' => 'Acetato']);
        $shape = Shape::factory()->create(['name' => 'Rectangular']);

        $product = Product::factory()->for($brand)->create([
            'model' => '82278',
            'slug' => 'modelo-82278',
            'material_id' => $material->id,
            'shape_id' => $shape->id,
            'lens_width' => 52,
            'bridge_width' => 18,
            'temple_length' => 140,
            'short_description' => 'Montura clásica y ligera.',
            'description' => 'Una montura cómoda para el uso diario.',
        ]);

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('Ray-Ban')
            ->assertSee('82278')
            ->assertSee('Acetato')
            ->assertSee('Rectangular')
            ->assertSee('Unisex')
            ->assertSee('52-18-140')
            ->assertSee('Montura clásica y ligera.')
            ->assertSee('Una montura cómoda para el uso diario.');
    }

    public function test_optional_fields_are_omitted_without_breaking_the_page(): void
    {
        $product = Product::factory()->create([
            'model' => '90001',
            'slug' => 'modelo-90001',
            'material_id' => null,
            'shape_id' => null,
            'lens_width' => null,
            'bridge_width' => null,
            'temple_length' => null,
            'short_description' => null,
            'description' => null,
        ]);

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('90001')
            ->assertDontSee('Medidas')
            ->assertDontSee('Material')
            ->assertDontSee('Forma')
            ->assertDontSee('Descripción');
    }

    public function test_a_product_with_only_one_measurement_hides_the_measurements_block(): void
    {
        $product = Product::factory()->create([
            'lens_width' => 52,
            'bridge_width' => null,
            'temple_length' => null,
        ]);

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertDontSee('Medidas (lente-puente-varilla)');
    }
}
