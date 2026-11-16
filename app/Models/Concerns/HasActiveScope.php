<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasActiveScope
{
    /**
     * Registros visibles en el sitio publico.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('is_active'), true);
    }
}
