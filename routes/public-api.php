<?php

use Illuminate\Support\Facades\Route;

Route::githubWebhooks('github-webhooks');

// Route::get('/benchmark', function () {
//     $articles = \Illuminate\Support\Facades\DB::table('articles')->select('title')->latest()->simplePaginate(1);

//     return response()->json($articles);
// });
