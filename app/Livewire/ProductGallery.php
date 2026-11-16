<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Variant;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductGallery extends Component
{
    public Product $product;

    public ?int $selectedVariantId = null;

    public ?int $selectedMediaId = null;

    public function mount(Product $product): void
    {
        $this->product = $product;
        $this->selectedVariantId = $this->product->variants()->value('id');
        $this->selectedMediaId = $this->images()->first()?->id;
    }

    #[On('variant-selected')]
    public function updateVariant(int $variantId): void
    {
        if (! $this->product->variants()->whereKey($variantId)->exists()) {
            return;
        }

        $this->selectedVariantId = $variantId;
        $this->selectedMediaId = $this->images()->first()?->id;
    }

    public function selectMedia(int $mediaId): void
    {
        if ($this->images()->contains('id', $mediaId)) {
            $this->selectedMediaId = $mediaId;
        }
    }

    public function selectedVariant(): ?Variant
    {
        if ($this->selectedVariantId === null) {
            return null;
        }

        return $this->product->variants->firstWhere('id', $this->selectedVariantId);
    }

    /**
     * Imagenes de la variante elegida; si no tiene, las del producto.
     *
     * @return Collection<int, Media>
     */
    public function images(): Collection
    {
        $variantImages = $this->selectedVariant()?->getMedia('images') ?? collect();

        return $variantImages->isNotEmpty()
            ? $variantImages
            : $this->product->getMedia('images');
    }

    public function selectedImage(): ?Media
    {
        return $this->images()->firstWhere('id', $this->selectedMediaId)
            ?? $this->images()->first();
    }

    public function render(): View
    {
        return view('livewire.product-gallery');
    }
}
