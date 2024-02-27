<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\View\Shared\HomeSeo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Notifications\UptimeMonitor;
use Illuminate\View\AnonymousComponent;
use GrahamCampbell\GitHub\Facades\GitHub;
use App\DataObjects\Front\GithubRepositoryData;
use App\Support\Http\Controllers\BaseController;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

use Illuminate\Support\Facades\{
    DB,
    Cache,
    Blade,
    Notification,
};

use App\Models\{
    Tag,
    User,
    Guest,
    Article,
    Category,
    UptimeWebhookCall,
};

use App\Http\Requests\{
    FrontSearchRequest,
    FrontUptimeWebhookRequest,
    FrontSubscribeNotificationRequest,
};

class FrontController extends BaseController
{
    private const UPTIME_DOWN_STATUS = 0;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Register user in web push to receive notifications.
     *
     * @param \App\Http\Requests\FrontSubscribeNotificationRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribeNotification(FrontSubscribeNotificationRequest $request): JsonResponse
    {
        $guest = Guest::firstOrCreate(['endpoint' => $endpoint = $request->input('endpoint')]);

        $subscription = $guest->updatePushSubscription(
            $endpoint,
            $request->input('public_key'),
            $request->input('auth_token'),
            $request->input('encoding'),
        );

        auth('guest')->login($guest);

        return response()->json([], 200);
    }

    /**
     * Register the logged-in user so that they can receive notifications.
     *
     * @param \App\Http\Requests\FrontSubscribeNotificationRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addUserToBeNotifiedToUptimeMonitor(FrontSubscribeNotificationRequest $request): JsonResponse
    {
        /** @var \App\Models\User */
        $user = auth()->user();

        DB::transaction(fn () =>
            $user->updateShouldBeNotified()
                 ->updatePushSubscription(
                    $request->input('endpoint'),
                    $request->input('public_key'),
                    $request->input('auth_token'),
                    $request->input('encoding'),
                 )
        );

        return response()->json(status: JsonResponse::HTTP_CREATED);
    }

    /**
     * @param \App\Http\Requests\FrontUptimeWebhookRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function uptimeWebhook(FrontUptimeWebhookRequest $request): JsonResponse
    {
        UptimeWebhookCall::create($request->validated());

        $message = trim($request->input('msg'), '"');

        if ($request->input('heartbeat')['status'] === self::UPTIME_DOWN_STATUS) {
            report($message);
        }

        Notification::send(User::onlyShouldBeNotified()->get(), new UptimeMonitor($message));

        return response()->json(status: JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Blog home page.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function home(Request $request)
    {
        $articles = $this->articlesOnHome();

        return view('pages::home', compact('articles'));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function articlesOnHome(): EloquentCollection
    {
        return Cache::rememberForever('latest-articles', fn () =>
            Article::with('tags')->latest()->get()
        );
    }

    /**
     * Blog home page for service worker.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function homeShowContent(Request $request): View
    {
        $articles = $this->articlesOnHome();

        $seoHTML = Blade::renderComponent(
            new AnonymousComponent('components.seo', $homeSeo = (new HomeSeo)())
        );

        return view('app-shell.pages.home', compact('seoHTML', 'articles') + $homeSeo);
    }

    /**
     * Renders the article details page.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Article $post
     *
     * @return \Illuminate\View\View
     */
    public function postShow(Request $request, Article $post): View
    {
        return view('pages::article', $this->postData($post));
    }

    /**
     * Renders only the article content. It will be used in the service worker's app-shell.
     *
     * @param \App\Models\Article $post
     *
     * @return \Illuminate\View\View
     */
    public function postShowContent(Article $post): View
    {
        $seoComponent = new AnonymousComponent('components.seo', [
            'title' => $post->title,
            'description' => $post->summary,
            'seoImage' => $post->image_url,
            'seoUrl' => route('web.posts.show', $post)
        ]);

        $seoComponent->withAttributes(['fullTitle' => true]);

        $seoHTML = Blade::renderComponent($seoComponent);

        return view('app-shell.pages.article', $this->postData($post) + compact('seoHTML'));
    }

    /**
     * Add a "like" to the article.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Article $post
     *
     * @return int|null
     */
    public function addLikePost(Request $request, Article $post): ?int
    {
        $post->increment('likes');
        $post->saveOrFail();

        $request->session()->put("likes.posts.{$post->getKey()}", true);

        return $post->likes;
    }

    /**
     * Remove the "like" from the article.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Article $post
     *
     * @return void
     */
    public function removeLikePost(Request $request, Article $post): int
    {
        $key = "likes.posts.{$post->getKey()}";

        if ($request->session()->exists($key)) {
            $request->session()->forget($key);

            $post->decrement('likes');
            $post->saveOrFail();
        }

        return $post->likes;
    }

    /**
     * Shows the page with the tag usage data.
     *
     * @param \App\Models\Article $article
     *
     * @return \Illuminate\View\View
     */
    public function tagsShow(Article $article): View
    {
        $tags = Tag::withCount('articles')->get();

        return view('pages::tags', compact('tags'));
    }

    /**
     * Shows the center page of the blog.
     *
     * @param \App\Http\Requests\FrontSearchRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function blog(FrontSearchRequest $request): View
    {
        $posts = Article::latest();

        if ($request->filled('tag')) {
            $posts = Article::searchByTags($request->input('tag'));
        } elseif ($request->filled('search')) {
            $posts = Article::fullSearch($request->input('search'));
        }

        $posts = $posts->with('tags')->get();

        return view('pages::blog', compact('posts'));
    }

    /**
     * Shows the category page with its respective articles.
     *
     * @param \App\Models\Category $category
     *
     * @return void
     */
    public function showCategoryPosts(Category $category): View
    {
        $posts = $category->posts()
                          ->latest('created_at')
                          ->get();

        return view('pages::category-posts', compact('category', 'posts'));
    }

    /**
     * Shows the "about me" page.
     *
     * @return \Illuminate\View\View
     */
    public function aboutMe(): View
    {
        /** @var \Spatie\LaravelData\DataCollection<\App\DataObjects\Front\GithubRepositoryData> */
        $repositoriesData = Cache::remember('repositories', (now()->diffInSeconds(now()->addHours(24))), function () {
            /** @var \Spatie\LaravelData\DataCollection */
            $repositories = GithubRepositoryData::collection([]);

            $nameOfRepositories = [
                'laravel-docker',
                'php-pre-commit',
                'laravel-artisan-domain-contexts',
                'laravel-multienv',
                'laravel-ddd',
                'php-pre-push',
            ];

            foreach($nameOfRepositories as $repositoryName) {
                /** @var \Github\Api\Repo */
                $repo = GitHub::repo();
                $repositoryData = $repo->show('allysonsilva', $repositoryName);

                $repositories[] = GithubRepositoryData::from($repositoryData);
            }

            return $repositories;
        });

        return view('pages::about', compact('repositoriesData'));
    }

    /**
     * Computes the data that is needed for the article detail page.
     *
     * @param \App\Models\Article $post
     *
     * @return array
     */
    private function postData(Article $post): array
    {
        $nextPost = $post->next;
        $previousPost = $post->previous;

        $hasLike = request()->session()->exists("likes.posts.{$post->getKey()}");
        $post->loadMissing('tags');

        app('LastModified')->set($post->updated_at);

        return compact('post', 'hasLike', 'nextPost', 'previousPost');
    }
}
