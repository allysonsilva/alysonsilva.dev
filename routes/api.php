<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TagController;
use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\HealthCheckController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->name('v1.')->group(function () {
    Route::apiResource('categories', CategoryController::class)
        ->parameters(['categories' => 'categoryUuid']);

    Route::apiResource('articles', ArticleController::class)
        ->parameters(['articles' => 'articleUuid']);

    Route::post('tags/{tagUuid}/articles/{articleUuid}/sync', [TagController::class, 'attachArticle']);
    Route::delete('tags/{tagUuid}/articles/{articleUuid}/sync', [TagController::class, 'detachArticle']);

    Route::apiResource('tags', TagController::class)
        ->parameters(['tags' => 'tagUuid']);
});
