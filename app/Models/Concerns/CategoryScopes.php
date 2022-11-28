<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait CategoryScopes
{
    /**
     * Filter/search a category by MySQL full text type fields.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchFullText(Builder $query, string $value): Builder
    {
        return $query->orWhereFullText(['title', 'description'], $value);
    }
}
