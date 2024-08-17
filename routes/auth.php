<?php

use Illuminate\Support\Facades\Route;

use Spatie\Health\Http\Controllers\{
    HealthCheckResultsController,
    HealthCheckJsonResultsController
};

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

    Route::get('/health', HealthCheckResultsController::class);
    Route::get('/health-detailed', HealthCheckJsonResultsController::class);
});
