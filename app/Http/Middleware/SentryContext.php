<?php

namespace App\Http\Middleware;

use Closure;
use Sentry\State\Scope;
use Illuminate\Http\Request;
use function Sentry\configureScope as sentryConfigureScope;

class SentryContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        sentryConfigureScope(function (Scope $scope): void {
            $scope->setTag('application.name', config('app.name'));
        });

        if (auth()->check() && app()->bound('sentry')) {
            sentryConfigureScope(function (Scope $scope): void {
                $user = auth()->user();

                $scope->setUser([
                    'id' => $user->getKey(),
                    // 'email' => $user->email,
                ]);
            });
        }

        return $next($request);
    }
}
