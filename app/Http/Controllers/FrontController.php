<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Guest;
use App\Models\Article;
use App\Models\Category;
use Illuminate\View\View;
use App\View\Shared\HomeSeo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\AnonymousComponent;
use App\Http\Requests\FrontSearchRequest;
use GrahamCampbell\GitHub\Facades\GitHub;
use App\DataObjects\Front\GithubRepositoryData;
use App\Support\Http\Controllers\BaseController;
use App\Http\Requests\FrontSubscribeNotificationRequest;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class FrontController extends BaseController
{
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
            $request->input('endpoint'),
            $request->input('public_key'),
            $request->input('auth_token'),
            $request->input('encoding'),
        );

        auth('guest')->login($guest);

        return response()->json([], 200);
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
