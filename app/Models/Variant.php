<?php

namespace App\Models;

use Database\Factories\VariantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Variant extends Model implements HasMedia
{
    /** @use HasFactory<VariantFactory> */
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'product_id',
        'color_name',
        'color_hex',
        'price',
        'sort',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'sort' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
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
