<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Variant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tests\TestCase;

class ProductMediaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_a_product_can_store_gallery_images(): void
    {
        $product = Product::factory()->create();

        $product->addMediaFromString($this->fakePng())
            ->usingFileName('montura-frontal.png')
            ->toMediaCollection('images');

        $this->assertCount(1, $product->getMedia('images'));
        $this->assertInstanceOf(Media::class, $product->getFirstMedia('images'));
    }

    public function test_a_variant_can_store_its_own_images(): void
    {
        $variant = Variant::factory()->create();

        $variant->addMediaFromString($this->fakePng())
            ->usingFileName('variante-negro.png')
            ->toMediaCollection('images');

        $this->assertCount(1, $variant->getMedia('images'));
    }

    private function fakePng(): string
    {
        return base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg=='
        );
    }
}
