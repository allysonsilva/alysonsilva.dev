<?php

namespace App\Http\QueryFilters\Article;

use App\Support\ORM\BaseEloquentBuilder;

class IndexSearch
{
    public function handle(BaseEloquentBuilder $builder, $next)
    {
        if (! empty($search = trim((string) request()->input('search')))) {
            $builder->searchFullText($search);
        }

        $builder->with('category', 'tags');

        return $next($builder)->latest();
    }
}
