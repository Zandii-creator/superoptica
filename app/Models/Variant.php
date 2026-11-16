<?php

namespace App\Models;

use Database\Factories\VariantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

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
}
