<?php

namespace Tests\Feature;

use App\Enums\Gender;
use App\Enums\ProductType;
use App\Models\Brand;
use App\Models\Material;
use App\Models\Product;
use App\Models\Shape;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CatalogPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_the_catalog_shows_only_published_products(): void
    {
        Product::factory()->create(['model' => '11111']);
        Product::factory()->create(['model' => '22222', 'is_active' => false]);
        Product::factory()->create(['model' => '33333', 'published_at' => null]);

        $this->get('/catalogo')
            ->assertOk()
            ->assertSee('11111')
            ->assertDontSee('22222')
            ->assertDontSee('33333');
    }

    public function test_it_filters_by_brand_slug(): void
    {
        $rayBan = Brand::factory()->create(['name' => 'Ray-Ban', 'slug' => 'ray-ban']);
        $oakley = Brand::factory()->create(['name' => 'Oakley', 'slug' => 'oakley']);

        Product::factory()->for($rayBan)->create(['model' => '11111']);
        Product::factory()->for($oakley)->create(['model' => '22222']);

        $this->get('/catalogo?marca=ray-ban')
            ->assertOk()
            ->assertSee('11111')
            ->assertDontSee('22222');
    }

    public function test_it_combines_brand_type_gender_material_and_shape_filters(): void
    {
        $brand = Brand::factory()->create(['slug' => 'ray-ban']);
        $material = Material::factory()->create(['slug' => 'acetato']);
        $shape = Shape::factory()->create(['slug' => 'rectangular']);

        Product::factory()->for($brand)->create([
            'model' => '11111',
            'type' => ProductType::Monturas,
            'gender' => Gender::Unisex,
            'material_id' => $material->id,
            'shape_id' => $shape->id,
        ]);

        Product::factory()->for($brand)->create([
            'model' => '22222',
            'type' => ProductType::Espejuelos,
            'gender' => Gender::Unisex,
            'material_id' => $material->id,
            'shape_id' => $shape->id,
        ]);

        $this->get('/catalogo?marca=ray-ban&tipo=monturas&genero=unisex&material=acetato&forma=rectangular')
            ->assertOk()
            ->assertSee('11111')
            ->assertDontSee('22222');
    }

    public function test_it_ignores_filters_with_unknown_values(): void
    {
        Product::factory()->create(['model' => '11111']);

        $this->get('/catalogo?tipo=inventado&material=no-existe')
            ->assertOk()
            ->assertSee('11111');
    }

    public function test_pagination_keeps_the_active_filters_in_the_links(): void
    {
        $brand = Brand::factory()->create(['slug' => 'ray-ban']);
        Product::factory()->count(13)->for($brand)->create();

        $this->get('/catalogo?marca=ray-ban')
            ->assertOk()
            ->assertSee('marca=ray-ban', false)
            ->assertSee('page=2', false);
    }

    public function test_the_catalog_shows_the_national_currency_notice(): void
    {
        Product::factory()->create();

        $this->get('/catalogo')
            ->assertOk()
            ->assertSee('Precios en moneda nacional (CUP).');
    }
}
