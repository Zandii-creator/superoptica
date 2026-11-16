<?php

namespace Tests\Feature;

use App\Enums\Gender;
use App\Enums\ProductType;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_active_and_published_products_are_visible(): void
    {
        $visible = Product::factory()->create(['published_at' => now()->subDay()]);
        Product::factory()->create(['is_active' => false]);
        Product::factory()->create(['published_at' => null]);
        Product::factory()->create(['published_at' => now()->addDay()]);

        $ids = Product::query()->published()->pluck('id');

        $this->assertSame([$visible->id], $ids->all());
    }

    public function test_featured_scope_returns_only_published_featured_products(): void
    {
        $featured = Product::factory()->create(['is_featured' => true]);
        Product::factory()->create(['is_featured' => true, 'is_active' => false]);
        Product::factory()->create(['is_featured' => false]);

        $ids = Product::query()->featured()->pluck('id');

        $this->assertSame([$featured->id], $ids->all());
    }

    public function test_active_scope_filters_inactive_brands(): void
    {
        $active = Brand::factory()->create();
        Brand::factory()->create(['is_active' => false]);

        $this->assertSame([$active->id], Brand::query()->active()->pluck('id')->all());
    }

    public function test_ordered_scope_sorts_services_by_sort_value(): void
    {
        $second = Service::factory()->create(['sort' => 2]);
        $first = Service::factory()->create(['sort' => 1]);

        $this->assertSame(
            [$first->id, $second->id],
            Service::query()->ordered()->pluck('id')->all()
        );
    }

    public function test_active_services_scope_excludes_inactive_services(): void
    {
        $active = Service::factory()->create();
        Service::factory()->create(['is_active' => false]);

        $this->assertSame([$active->id], Service::query()->active()->pluck('id')->all());
    }

    public function test_type_and_gender_are_cast_to_enums(): void
    {
        $product = Product::factory()->create([
            'type' => 'monturas',
            'gender' => 'femenino',
        ])->fresh();

        $this->assertSame(ProductType::Monturas, $product->type);
        $this->assertSame(Gender::Femenino, $product->gender);
        $this->assertSame('Monturas', $product->type->getLabel());
        $this->assertSame('Femenino', $product->gender->getLabel());
    }
}
