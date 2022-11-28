<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait ArticleScopes
{
    /**
     * Filter/search a article by MySQL full text type fields.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchFullText(Builder $query, string $value): Builder
    {
        return $query->orWhereFullText(['title', 'content', 'summary'], $value);
    }

    /**
     * Filter articles by tags.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchByTags(Builder $query, string $value): Builder
    {
        return $query->orWhereHas('tags', function (Builder $query) use ($value) {
            $query->where('title', 'LIKE', "%{$value}%")
                  ->orWhere('slug', $value);
        });
    }

    /**
     * Filter articles by certain category.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchByCategory(Builder $query, string $value): Builder
    {
        return $query->orWhereHas('category', function (Builder $query) use ($value) {
            $query->searchFullText($value);
        });
    }

    /**
     * Filter articles more completely.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFullSearch(Builder $query, string $value): Builder
    {
        return $query->searchFullText($value)
                     ->searchByTags($value);
    }
}
