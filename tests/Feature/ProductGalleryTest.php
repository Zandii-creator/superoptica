<?php

namespace Tests\Feature;

use App\Livewire\ProductGallery;
use App\Livewire\ProductVariantSelector;
use App\Models\Brand;
use App\Models\Product;
use App\Models\SiteSettings;
use App\Models\Variant;
use App\Support\WhatsApp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ProductGalleryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        Storage::fake('public');
        SiteSettings::current()->update(['whatsapp_number' => '5355123456']);
    }

    public function test_the_selector_preselects_the_first_variant(): void
    {
        $product = Product::factory()->create();
        $first = Variant::factory()->for($product)->create(['color_name' => 'Negro', 'sort' => 0]);
        Variant::factory()->for($product)->create(['color_name' => 'Carey', 'sort' => 1]);

        Livewire::test(ProductVariantSelector::class, ['product' => $product])
            ->assertSet('selectedVariantId', $first->id)
            ->assertSee('Negro');
    }

    public function test_selecting_a_color_updates_the_gallery_to_that_variant_images(): void
    {
        $product = Product::factory()->create();
        $black = Variant::factory()->for($product)->create(['color_name' => 'Negro', 'sort' => 0]);
        $carey = Variant::factory()->for($product)->create(['color_name' => 'Carey', 'sort' => 1]);

        $blackImage = $black->addMediaFromString($this->fakePng())
            ->usingFileName('negro.png')
            ->toMediaCollection('images');

        $careyImage = $carey->addMediaFromString($this->fakePng())
            ->usingFileName('carey.png')
            ->toMediaCollection('images');

        $gallery = Livewire::test(ProductGallery::class, ['product' => $product->fresh()]);

        $gallery->assertSee($blackImage->getUrl('detail'), false);

        $gallery
            ->dispatch('variant-selected', variantId: $carey->id)
            ->assertSet('selectedVariantId', $carey->id)
            ->assertSee($careyImage->getUrl('detail'), false)
            ->assertDontSee($blackImage->getUrl('detail'), false);
    }

    public function test_a_variant_without_images_falls_back_to_the_product_gallery(): void
    {
        $product = Product::factory()->create();

        $productImage = $product->addMediaFromString($this->fakePng())
            ->usingFileName('producto.png')
            ->toMediaCollection('images');

        $variant = Variant::factory()->for($product)->create(['color_name' => 'Plateado']);

        Livewire::test(ProductGallery::class, ['product' => $product->fresh()])
            ->dispatch('variant-selected', variantId: $variant->id)
            ->assertSee($productImage->getUrl('detail'), false);
    }

    public function test_the_whatsapp_link_mentions_the_model_and_the_selected_color(): void
    {
        $brand = Brand::factory()->create(['name' => 'Ray-Ban']);
        $product = Product::factory()->for($brand)->create(['model' => '82278']);

        $black = Variant::factory()->for($product)->create(['color_name' => 'Negro', 'sort' => 0]);
        $carey = Variant::factory()->for($product)->create(['color_name' => 'Carey', 'sort' => 1]);

        $component = Livewire::test(ProductVariantSelector::class, ['product' => $product]);

        $component->assertSee(WhatsApp::productLink($product, $black), false);

        $component
            ->call('selectVariant', $carey->id)
            ->assertSee(WhatsApp::productLink($product, $carey), false)
            ->assertSee('Carey')
            ->assertSee('82278');
    }

    public function test_a_variant_from_another_product_is_ignored(): void
    {
        $product = Product::factory()->create();
        $first = Variant::factory()->for($product)->create(['sort' => 0]);
        $foreign = Variant::factory()->create();

        Livewire::test(ProductVariantSelector::class, ['product' => $product])
            ->call('selectVariant', $foreign->id)
            ->assertSet('selectedVariantId', $first->id);
    }

    public function test_a_product_without_variants_still_offers_a_whatsapp_link(): void
    {
        $product = Product::factory()->create(['model' => '82278']);

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee(WhatsApp::productLink($product), false);
    }

    private function fakePng(): string
    {
        return base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg=='
        );
    }
}
