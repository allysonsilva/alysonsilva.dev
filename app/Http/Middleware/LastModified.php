<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class LastModified
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        /** @var \Carbon\Carbon|null */
        $lastModified = app('LastModified')->get();

        if (! empty($lastModified) && $response instanceof Response) {
            $response->header('Last-Modified', $lastModified->toRfc7231String());
        }

        return $response;
    }
}
