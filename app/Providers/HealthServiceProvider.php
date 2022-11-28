<?php

namespace App\Providers;

use Spatie\Health\Facades\Health;
use Illuminate\Support\ServiceProvider;

use Spatie\Health\Checks\Checks\{
    RedisCheck,
    CacheCheck,
    DatabaseCheck,
    DebugModeCheck,
    EnvironmentCheck,
    OptimizedAppCheck
};

class HealthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $checks = [
            CacheCheck::new(),
            RedisCheck::new(),
            DatabaseCheck::new(),
            OptimizedAppCheck::new(),
        ];

        if ($this->app->isProduction()):
            $checks = array_merge($checks, [DebugModeCheck::new(), EnvironmentCheck::new()]);
        endif;

        Health::checks($checks);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
