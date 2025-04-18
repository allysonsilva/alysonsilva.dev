<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Laravel\Telescope\EntryType;
use Laravel\Telescope\Telescope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Illuminate\Support\Facades\Route;
use Laravel\Telescope\Http\Middleware\Authorize;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Configure the Telescope authorization services.
     *
     * @return void
     */
    protected function authorization()
    {
        $this->gate();

        if ($this->app->request->is(config('telescope.path') . '*')) {
            Auth::shouldUse('admin');

            if (! app()->isLocal()) {
                Route::middlewareGroup('telescope', ['web', 'auth:admin', Authorize::class]);
            }
        }

        Telescope::auth(function ($request) {
            return app()->isLocal() ||
                   Gate::authorize('viewTelescope', [$request->user()]);
        });
    }

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
