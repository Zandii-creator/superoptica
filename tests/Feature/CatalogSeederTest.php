<?php

namespace Tests\Feature;

use App\Models\Material;
use App\Models\Service;
use App\Models\Shape;
use Database\Seeders\CatalogDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_seeds_the_materials_used_by_the_filters(): void
    {
        $this->seed(CatalogDataSeeder::class);

        $this->assertSame(
            ['acetato', 'metal', 'titanio', 'tr90', 'acero-inoxidable'],
            Material::query()->orderBy('sort')->pluck('slug')->all()
        );

        $this->assertTrue(Material::query()->where('slug', 'acetato')->first()->is_active);
    }

    public function test_it_seeds_the_shapes_used_by_the_filters(): void
    {
        $this->seed(CatalogDataSeeder::class);

        $this->assertSame(
            ['rectangular', 'redonda', 'cuadrada', 'aviador', 'gato'],
            Shape::query()->orderBy('sort')->pluck('slug')->all()
        );
    }

    public function test_it_seeds_the_services_in_business_order(): void
    {
        $this->seed(CatalogDataSeeder::class);

        $this->assertSame(
            ['examen-visual', 'ajuste-de-espejuelos', 'graduacion'],
            Service::query()->ordered()->pluck('slug')->all()
        );
    }

    public function test_seeding_twice_does_not_duplicate_the_taxonomies(): void
    {
        $this->seed(CatalogDataSeeder::class);
        $this->seed(CatalogDataSeeder::class);

        $this->assertSame(5, Material::query()->count());
        $this->assertSame(5, Shape::query()->count());
        $this->assertSame(3, Service::query()->count());
    }
}
