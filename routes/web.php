<?php

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\FrontController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::controller(FrontController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/index.content.html', 'homeShowContent')->name('home.show-content');

    Route::get('/blog', 'blog')->name('blog.posts');

    Route::post('/notifications/subscribe', 'subscribeNotification')->name('notifications.subscribe');

    Route::get('/blog/{post}', 'postShow')->name('posts.show');
    Route::get('/blog/{post}/index.content.html', 'postShowContent')->name('posts.show-content');

    Route::post('/blog/{post}/like', 'addLikePost')->name('posts.add-like');
    Route::delete('/blog/{post}/like', 'removeLikePost')->name('posts.remove-like');

    Route::get('/categories/{category}', 'showCategoryPosts')->name('blog.category-posts.show');

    Route::get('/tags', 'tagsShow')->name('tags.show');
    Route::get('/hi', 'aboutMe')->name('about-me');

    Route::middleware('auth:sanctum')->name('logged.')->group(function () {
        Route::post('/notifications/add-user-to-uptime-monitor', 'addUserToBeNotifiedToUptimeMonitor')->name('notifications.add-user-to-uptime-monitor');
        Route::post('/uptime/webhook', 'uptimeWebhook')->name('uptime.webhook');
    });
});

// Route::get('/benchmark', function () {
//     $articles = \Illuminate\Support\Facades\DB::table('articles')->select('title')->latest()->simplePaginate(1);

//     return response()->json($articles);
// });

Route::view('/policies', 'pages.policies')->name('policies.show');

Route::get('/feed', fn () => response()->view('public::feed', [], 200)
                                       ->header('Content-Type', 'application/xml; charset=UTF-8')
                                       ->header('Cache-Control', 'no-cache, private'))
                                       ->name('show.feed');

Route::get('/rss/' . sha1(Str::random(10)), FeedController::class)->name('rss.xml');

Route::get('version', fn () => response()->json('v3'));

Route::get('/signed-login/{admin}', function (Request $request, User $admin) {
    // if (app()->isProduction()) {
    //     return;
    // }

    // Set one week session lifetime (In minutes)
    config(['session.lifetime' => 1 * (60 * 24 * 7)]);

    auth()->login($admin);

    return Redirect::route('auth.logged.me.profile');
})->middleware('signed')->name('signed-login');
