<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Laravel\Telescope\EntryType;
use Laravel\Telescope\Telescope;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Foundation\Application;
use Laravel\Telescope\Http\Middleware\Authorize;
use Laravel\Telescope\TelescopeApplicationServiceProvider;
use App\Http\Middleware\Authenticate as AuthenticateMiddleware;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        Telescope::night();

        $this->hideSensitiveRequestDetails();

        Telescope::tag(function (IncomingEntry $entry) {
            $tags = [];

            if ($entry->type === EntryType::REQUEST) {
                $tags = array_merge([
                    'status:' . $entry->content['response_status'],
                    'uri:' . Str::slug(trim(parse_url($entry->content['uri'], PHP_URL_PATH), '/')),
                ], $tags);
            }

            return $tags;
        });

        Telescope::filter(function (IncomingEntry $entry) {
            return true;

            return $entry->isReportableException() ||
                   $entry->isFailedRequest() ||
                   $entry->isFailedJob() ||
                   $entry->isScheduledTask() ||
                   $entry->hasMonitoredTag();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        parent::boot();

        // $this->booted(function () {
        //     $this->app['router']->pushMiddlewareToGroup('telescope', AuthenticateMiddleware::using('management'));
        // });

        $this->callAfterResolving('router', function (Router $router, Application $app) {
            if ($this->app->isLocal()) return;

            if (Str::is($router->current()->getDomain(), config('telescope.domain'))) {
                Auth::shouldUse('admin');

                Route::middlewareGroup('telescope', ['web', AuthenticateMiddleware::using('admin'), Authorize::class,]);
            }
        });
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     *
     * @return void
     */
    protected function hideSensitiveRequestDetails()
    {
        if (app()->isLocal()) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewTelescope', function ($user) {
            return in_array($user->email, config('telescope.emails_allowed'));
        });
    }
}
