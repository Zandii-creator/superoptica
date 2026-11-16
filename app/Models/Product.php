<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\ProductType;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'brand_id',
        'material_id',
        'shape_id',
        'model',
        'slug',
        'type',
        'gender',
        'lens_width',
        'bridge_width',
        'temple_length',
        'price_from',
        'short_description',
        'description',
        'is_featured',
        'is_active',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'type' => ProductType::class,
            'gender' => Gender::class,
            'lens_width' => 'integer',
            'bridge_width' => 'integer',
            'temple_length' => 'integer',
            'price_from' => 'integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function shape(): BelongsTo
    {
        return $this->belongsTo(Shape::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class)->orderBy('sort');
    }

    /**
     * Productos visibles en el sitio publico: activos y ya publicados.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Productos destacados de la pagina de inicio.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->published()->where('is_featured', true);
    }

    /**
     * Precio referencial: el mas bajo de las variantes con precio; si no,
     * el precio del producto.
     */
    public function referencePrice(): int
    {
        $variants = $this->relationLoaded('variants')
            ? $this->variants
            : $this->variants()->get();

        $lowest = $variants->whereNotNull('price')->min('price');

        return (int) ($lowest ?? $this->price_from);
    }

    /**
     * Nombre comercial para titulos y textos alternativos.
     */
    public function displayName(): string
    {
        return trim(($this->brand?->name ?? '').' '.$this->model);
    }

    /**
     * Medidas en formato lente-puente-varilla; null si falta alguna.
     */
    public function measurementsLabel(): ?string
    {
        if ($this->lens_width === null || $this->bridge_width === null || $this->temple_length === null) {
            return null;
        }

        return $this->lens_width.'-'.$this->bridge_width.'-'.$this->temple_length;
    }

    public function coverUrl(string $conversion = 'card'): ?string
    {
        $media = $this->getFirstMedia('images');

        if ($media === null) {
            return null;
        }

        return $media->hasGeneratedConversion($conversion)
            ? $media->getUrl($conversion)
            : $media->getUrl();
    }

    public function isPubliclyVisible(): bool
    {
        return $this->is_active
            && $this->published_at !== null
            && $this->published_at->isPast();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(160)
            ->height(120)
            ->format('webp')
            ->nonQueued();

        $this->addMediaConversion('card')
            ->width(480)
            ->height(360)
            ->format('webp')
            ->nonQueued();

        $this->addMediaConversion('detail')
            ->width(960)
            ->height(960)
            ->format('webp')
            ->nonQueued();
    }
}
