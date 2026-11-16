<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Material;
use App\Models\Product;
use App\Models\Shape;
use App\Models\Variant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_brand_has_many_products(): void
    {
        $brand = Brand::factory()->create();
        Product::factory()->count(2)->for($brand)->create();

        $this->assertCount(2, $brand->products);
        $this->assertInstanceOf(Product::class, $brand->products->first());
    }

    public function test_a_product_belongs_to_a_brand_material_and_shape(): void
    {
        $product = Product::factory()->create();

        $this->assertInstanceOf(Brand::class, $product->brand);
        $this->assertInstanceOf(Material::class, $product->material);
        $this->assertInstanceOf(Shape::class, $product->shape);
    }

    public function test_a_product_has_many_variants(): void
    {
        $product = Product::factory()->create();
        Variant::factory()->count(3)->for($product)->create();

        $this->assertCount(3, $product->variants);
    }

    public function test_a_variant_belongs_to_a_product(): void
    {
        $variant = Variant::factory()->create();

        $this->assertInstanceOf(Product::class, $variant->product);
    }

    public function test_product_measurements_are_optional(): void
    {
        $product = Product::factory()->create([
            'lens_width' => null,
            'bridge_width' => null,
            'temple_length' => null,
        ]);

        $this->assertNull($product->fresh()->lens_width);
    }
}
