<?php

namespace App\Providers\API;

use App\Models\Tag;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureExplicitBinding();
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware(['api', 'throttle:public-api'])
                ->name('public-api.')
                ->prefix('api')
                ->group(base_path('routes/public-api.php'));

            Route::middleware(['api', 'auth:sanctum', 'throttle:api'])
                ->name('api.')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
        });
    }

    protected function configureExplicitBinding(): void
    {
        Route::bind('categoryUuid', fn (string $value) => Category::where('id', $value)->firstOrFail());

        Route::bind('articleUuid', fn (string $value) => Article::where('id', $value)->firstOrFail());

        Route::bind('tagUuid', fn (string $value) => Tag::where('id', $value)->firstOrFail());
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('public-api', function (Request $request) {
            return Limit::perMinute(120)->by($request->ip());
        });

        RateLimiter::for('api', function (Request $request) {
            return $request->user()
                ? Limit::perMinute(120)->by($request->user()->getKey())
                : Limit::perMinute(60)->by($request->ip());
        });
    }
}
