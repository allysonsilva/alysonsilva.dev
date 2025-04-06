<?php

use App\Http\Controllers\HealthCheckController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
|
*/

Route::middleware('auth:sanctum')->name('logged.')->group(function () {
    Route::name('me.')->group(function () {
        Route::get('me/profile', fn() => auth()->user())->name('profile');
    });

    // /healthz?view&fresh
    // /healthz?exception&fresh
    // /healthz?json&fresh
    Route::get('healthz', HealthCheckController::class)->name('health-check');
});
