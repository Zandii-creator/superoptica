<?php

namespace Tests\Feature;

use App\Livewire\CatalogSearch;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;
use Tests\TestCase;

class CatalogSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_search_by_model_works_without_javascript(): void
    {
        Product::factory()->create(['model' => '82278']);
        Product::factory()->create(['model' => '11111']);

        $this->get('/catalogo?q=82278')
            ->assertOk()
            ->assertSee('82278')
            ->assertDontSee('11111');
    }

    public function test_search_by_brand_name_works_without_javascript(): void
    {
        $rayBan = Brand::factory()->create(['name' => 'Ray-Ban', 'slug' => 'ray-ban']);
        $oakley = Brand::factory()->create(['name' => 'Oakley', 'slug' => 'oakley']);

        Product::factory()->for($rayBan)->create(['model' => '82278']);
        Product::factory()->for($oakley)->create(['model' => '11111']);

        $this->get('/catalogo?q=Ray-Ban')
            ->assertOk()
            ->assertSee('82278')
            ->assertDontSee('11111');
    }

    public function test_the_search_form_is_a_plain_get_form(): void
    {
        Product::factory()->create();

        $this->get('/catalogo')
            ->assertOk()
            ->assertSee('action="'.route('catalog.index').'"', false)
            ->assertSee('name="q"', false);
    }

    public function test_the_live_search_redirects_to_the_same_filtered_url(): void
    {
        Livewire::test(CatalogSearch::class)
            ->set('q', '82278')
            ->assertRedirect(route('catalog.index', ['q' => '82278']));
    }

    public function test_the_live_search_keeps_the_active_filters(): void
    {
        Livewire::test(CatalogSearch::class, ['activeFilters' => ['marca' => 'ray-ban']])
            ->set('q', '82278')
            ->assertRedirect(route('catalog.index', ['marca' => 'ray-ban', 'q' => '82278']));
    }
}
