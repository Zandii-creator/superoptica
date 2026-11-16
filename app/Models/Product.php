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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
    }
}
