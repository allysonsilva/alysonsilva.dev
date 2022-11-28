<?php

namespace App\Http\QueryFilters\Category;

use App\Support\ORM\BaseEloquentBuilder;

class IndexSearch
{
    public function handle(BaseEloquentBuilder $builder, $next)
    {
        $title = trim((string) request()->input('title'));
        $description = trim((string) request()->input('description'));

        $search = trim($title . ' ' . $description);

        if (! empty($search)) {
            $builder->searchFullText($search);
        }

        return $next($builder);
    }
}
