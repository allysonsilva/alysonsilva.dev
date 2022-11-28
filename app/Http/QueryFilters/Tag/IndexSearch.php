<?php

namespace App\Http\QueryFilters\Tag;

use App\Support\ORM\BaseEloquentBuilder;

class IndexSearch
{
    public function handle(BaseEloquentBuilder $builder, $next)
    {
        if (! empty($title = trim((string) request()->input('title')))) {
            $builder->where('title', 'like', "%{$title}%");
        }

        $builder->withCount('articles');

        return $next($builder)->latest();
    }
}
