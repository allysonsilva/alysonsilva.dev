<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->routes(function () {
            Route::middleware('web')
                ->name('web.')
                ->group(base_path('routes/web.php'));

            Route::middleware('web')
                ->name('auth.')
                ->group(base_path('routes/auth.php'));
        });
    }
}
