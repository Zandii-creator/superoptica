<?php

namespace App\Models;

use App\Models\Concerns\HasActiveScope;
use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    /** @use HasFactory<ServiceFactory> */
    use HasActiveScope, HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
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

    /**
     * Servicios en el orden definido por el negocio.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort')->orderBy('name');
    }
}
