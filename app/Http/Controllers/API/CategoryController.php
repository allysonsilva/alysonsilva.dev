<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Pipeline\Pipeline;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\IndexCategoryRequest;
use App\Http\Requests\API\StoreCategoryRequest;
use App\Http\QueryFilters\Category\IndexSearch;
use App\Http\QueryFilters\Category\IndexOrderBy;
use App\Support\Http\Controllers\BaseController;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryController extends BaseController
{
    public function __construct(private Category $repository)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param \App\Http\Requests\IndexCategoryRequest $request
     * @param \Illuminate\Pipeline\Pipeline $pipeline
     *
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function index(IndexCategoryRequest $request, Pipeline $pipeline): ResourceCollection
    {
        $query = $pipeline->send($this->repository::query())
                          ->through([IndexSearch::class, IndexOrderBy::class])
                          ->thenReturn();

        return CategoryResource::collection($query->simplePaginate($request->input('per_page')));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreCategoryRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = $this->repository
                         ->newInstance()
                         ->fill($request->validated());

        $category->saveOrFail();

        return (new CategoryResource($category))
                    ->response()
                    ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Category $category
     *
     * @return \App\Http\Resources\CategoryResource
     */
    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\StoreCategoryRequest $request
     * @param \App\Models\Category $category
     *
     * @return \App\Http\Resources\CategoryResource
     */
    public function update(StoreCategoryRequest $request, Category $category): CategoryResource
    {
        $category->fill($request->validated());

        $category->saveOrFail();

        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Category $category
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json(status: JsonResponse::HTTP_NO_CONTENT);
    }
}
