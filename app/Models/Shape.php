<?php

namespace App\Models;

use App\Models\Concerns\HasActiveScope;
use Database\Factories\ShapeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shape extends Model
{
    /** @use HasFactory<ShapeFactory> */
    use HasActiveScope, HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sort',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sort' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
