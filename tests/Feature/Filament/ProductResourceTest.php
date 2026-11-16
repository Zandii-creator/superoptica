<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Brands\Pages\CreateBrand;
use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Services\Pages\CreateService;
use App\Models\Brand;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ProductResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->actingAs(User::factory()->create());
    }

    public function test_an_admin_creates_a_product_with_a_variant_and_an_image(): void
    {
        $brand = Brand::factory()->create();

        Livewire::test(CreateProduct::class)
            ->fillForm([
                'brand_id' => $brand->id,
                'model' => '82278',
                'slug' => 'modelo-82278',
                'type' => 'monturas',
                'gender' => 'unisex',
                'price_from' => 1500,
                'is_active' => true,
                'is_featured' => false,
                'published_at' => now(),
                'images' => [UploadedFile::fake()->image('montura.png')],
                'variants' => [
                    ['color_name' => 'Negro', 'price' => 1500, 'sort' => 0],
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $product = Product::query()->where('model', '82278')->firstOrFail();

        $this->assertSame(1500, $product->price_from);
        $this->assertCount(1, $product->variants);
        $this->assertSame('Negro', $product->variants->first()->color_name);
        $this->assertCount(1, $product->getMedia('images'));
    }

    public function test_a_brand_can_be_created_from_the_panel(): void
    {
        Livewire::test(CreateBrand::class)
            ->fillForm([
                'name' => 'Ray-Ban',
                'slug' => 'ray-ban',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('brands', ['slug' => 'ray-ban']);
    }

    public function test_a_service_can_be_created_from_the_panel(): void
    {
        Livewire::test(CreateService::class)
            ->fillForm([
                'name' => 'Examen visual',
                'slug' => 'examen-visual',
                'description' => 'Revisión de la vista con equipos del local.',
                'sort' => 1,
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('services', ['slug' => 'examen-visual']);
    }

    public function test_the_panel_rejects_a_product_without_a_brand(): void
    {
        Livewire::test(CreateProduct::class)
            ->fillForm([
                'model' => '99999',
                'slug' => 'modelo-99999',
                'type' => 'monturas',
                'gender' => 'unisex',
                'price_from' => 1500,
            ])
            ->call('create')
            ->assertHasFormErrors(['brand_id' => 'required']);

        $this->assertDatabaseMissing('products', ['model' => '99999']);
    }
}
