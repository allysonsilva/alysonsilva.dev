<?php

namespace App\Providers;

use Carbon\Carbon;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        Sanctum::ignoreMigrations();

        $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
        $this->app->register(TelescopeServiceProvider::class);

        // \Laravel\Telescope\Telescope::ignoreMigrations();

        $this->registerLastModifiedHandling();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPackagesMigrations();

        if (! $this->app->isLocal()) {
            URL::forceScheme('https');
        }

        // Tell Carbon to use the current app locale
        Carbon::setlocale(app()->getLocale());
    }

    /**
     * Register the package's migrations.
     *
     * @return void
     */
    private function registerPackagesMigrations()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(database_path('migrations/packages'));
        }
    }

    private function registerLastModifiedHandling(): void
    {
        $this->app->singleton('LastModified', function () {
            return new class {
                private ?Carbon $updatedAt = null;

                public function set(Carbon $updatedAt): void
                {
                    $this->updatedAt = $updatedAt;
                }

                public function get(): ?Carbon
                {
                    return $this->updatedAt;
                }
            };
        });
    }
}
