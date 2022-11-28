<?php

namespace App\Http\QueryFilters\Category;

use App\Support\ORM\BaseEloquentBuilder;

class IndexOrderBy
{
    public function handle(BaseEloquentBuilder $builder, $next)
    {
        $builder->latest();

        return $next($builder);
    }
}
