<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\SiteSettings;
use App\Models\Variant;
use App\Support\WhatsApp;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ProductVariantSelector extends Component
{
    public Product $product;

    public ?int $selectedVariantId = null;

    public function mount(Product $product): void
    {
        $this->product = $product;
        $this->selectedVariantId = $this->product->variants()->value('id');
    }

    public function selectVariant(int $variantId): void
    {
        if (! $this->product->variants()->whereKey($variantId)->exists()) {
            return;
        }

        $this->selectedVariantId = $variantId;

        $this->dispatch('variant-selected', variantId: $variantId);
    }

    public function selectedVariant(): ?Variant
    {
        if ($this->selectedVariantId === null) {
            return null;
        }

        return $this->product->variants->firstWhere('id', $this->selectedVariantId);
    }

    public function whatsappUrl(): ?string
    {
        if (blank(SiteSettings::current()->whatsapp_number)) {
            return null;
        }

        return WhatsApp::productLink($this->product, $this->selectedVariant());
    }

    public function render(): View
    {
        return view('livewire.product-variant-selector');
    }
}
