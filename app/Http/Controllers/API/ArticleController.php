<?php

namespace App\Http\Controllers\API;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use App\Actions\UpsertArticleAction;
use App\DataObjects\API\ArticleData;
use App\Http\Resources\ArticleResource;
use App\Http\QueryFilters\Article\IndexSearch;
use App\Support\Http\Controllers\BaseController;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ArticleController extends BaseController
{
    public function __construct(private Article $repository)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Pipeline\Pipeline $pipeline
     *
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function index(Request $request, Pipeline $pipeline): ResourceCollection
    {
        $query = $pipeline->send($this->repository::query())
                          ->through([IndexSearch::class])
                          ->thenReturn();

        return ArticleResource::collection($query->simplePaginate($request->input('per_page') ?? null));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\DataObjects\API\ArticleData $data
     * @param \App\Actions\UpsertArticleAction $action
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ArticleData $data, UpsertArticleAction $action): JsonResponse
    {
        $savedArticle = DB::transaction(fn () => $action->execute($data));

        return (new ArticleResource($savedArticle))
                    ->response()
                    ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Article $article
     *
     * @return \App\Http\Resources\ArticleResource
     */
    public function show(Article $article): ArticleResource
    {
        return new ArticleResource($article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\DataObjects\API\ArticleData $data
     * @param \App\Actions\UpsertArticleAction $action
     * @param \App\Models\Article $article
     *
     * @return \App\Http\Resources\ArticleResource
     */
    public function update(ArticleData $data, UpsertArticleAction $action, Article $article): ArticleResource
    {
        $updatedArticle = DB::transaction(fn () => $action->execute($data, $article));

        return (new ArticleResource($updatedArticle));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Article $article
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Article $article): JsonResponse
    {
        $article->delete();

        return response()->json(status: JsonResponse::HTTP_NO_CONTENT);
    }
}
