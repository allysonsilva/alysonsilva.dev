<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Support\Reflector;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            if (app()->bound('sentry') &&
                ! app()->isLocal() &&
                $this->shouldReport($e) &&
                ! app()->runningUnitTests()) {
                    if (Reflector::isCallable($dontReportCallable = [$e, 'dontReport']) &&
                        $this->container->call($dontReportCallable) === true) {

                        return false;
                    }

                    app('sentry')->captureException($e);

                    // If you wish to stop the propagation of the exception to the default logging stack,
                    // you may use the stop method when defining your reporting callback or return false from the callback
                    return false;
            }
        });
    }
}
