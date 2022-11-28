<?php

namespace App\Http\Middleware;

use Closure;

class TrackLastActiveAt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     *
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        /** @var \App\Models\Guest */
        $guestUser = $request->user('guest');

        if (! $guestUser) {
            return $next($request);
        }

        if (! $guestUser->last_active_at || $guestUser->last_active_at->isPast()) {
            $guestUser->update([
                'last_active_at' => now(),
            ]);
        }

        return $next($request);
    }
}
