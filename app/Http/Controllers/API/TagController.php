<?php

namespace App\Http\Controllers\API;

use App\Models\Tag;
use App\Models\Article;
use Illuminate\Http\Request;
use App\DataObjects\API\TagData;
use Illuminate\Http\JsonResponse;
use Illuminate\Pipeline\Pipeline;
use App\Http\Resources\TagResource;
use App\Http\QueryFilters\Tag\IndexSearch;
use App\Support\Http\Controllers\BaseController;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TagController extends BaseController
{
    public function __construct(private Tag $repository)
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

        return TagResource::collection($query->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\DataObjects\API\TagData $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TagData $data): JsonResponse
    {
        $tag = $this->repository
                    ->newInstance()
                    ->fill($data->validated());

        $tag->saveOrFail();

        return (new TagResource($tag))
                    ->response()
                    ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Tag $tag
     *
     * @return \App\Http\Resources\TagResource
     */
    public function show(Tag $tag): TagResource
    {
        return new TagResource($tag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\DataObjects\API\TagData $data
     * @param \App\Models\Tag $tag
     *
     * @return \App\Http\Resources\TagResource
     */
    public function update(TagData $data, Tag $tag): TagResource
    {
        $tag->fill($data->validated());

        $tag->saveOrFail();

        return new TagResource($tag);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Tag $tag
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Tag $tag): JsonResponse
    {
        $tag->forceDelete();

        return response()->json(status: JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Associates the tag with the article.
     *
     * @param \App\Models\Tag $tag
     * @param \App\Models\Article $article
     *
     * @return \App\Http\Resources\TagResource
     */
    public function attachArticle(Tag $tag, Article $article): TagResource
    {
        $tag->articles()->syncWithoutDetaching([$article->getKey()]);

        return new TagResource($tag);
    }

    /**
     * Removes the tag's association with the article.
     *
     * @param \App\Models\Tag $tag
     * @param \App\Models\Article $article
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detachArticle(Tag $tag, Article $article): JsonResponse
    {
        $tag->articles()->detach($article->getKey());

        return response()->json(status: JsonResponse::HTTP_NO_CONTENT);
    }
}
