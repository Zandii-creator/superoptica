<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Tests\TestCase;

class ProductMediaConversionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        Storage::fake('public');
    }

    public function test_an_uploaded_image_generates_the_webp_conversions(): void
    {
        $product = Product::factory()->create();

        $media = $product->addMediaFromString($this->fakePng())
            ->usingFileName('montura.png')
            ->toMediaCollection('images')
            ->fresh();

        $this->assertTrue($media->hasGeneratedConversion('thumb'));
        $this->assertTrue($media->hasGeneratedConversion('card'));
        $this->assertTrue($media->hasGeneratedConversion('detail'));

        $this->assertStringEndsWith('.webp', $media->getUrl('card'));
        $this->assertStringEndsWith('.webp', $media->getUrl('detail'));
        $this->assertStringEndsWith('.webp', $product->fresh()->coverUrl());
    }

    public function test_the_catalog_card_serves_the_card_conversion_with_lazy_loading(): void
    {
        $product = Product::factory()->create();

        $product->addMediaFromString($this->fakePng())
            ->usingFileName('montura.png')
            ->toMediaCollection('images');

        $this->get('/catalogo')
            ->assertOk()
            ->assertSee('loading="lazy"', false)
            ->assertSee($product->fresh()->coverUrl(), false);
    }

    public function test_the_product_page_shows_the_detail_conversion(): void
    {
        $product = Product::factory()->create();

        $media = $product->addMediaFromString($this->fakePng())
            ->usingFileName('montura.png')
            ->toMediaCollection('images');

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee($media->getUrl('detail'), false);
    }

    private function fakePng(): string
    {
        return ImageManager::gd()
            ->create(800, 600)
            ->fill('#dbeafe')
            ->toPng()
            ->toString();
    }
}
