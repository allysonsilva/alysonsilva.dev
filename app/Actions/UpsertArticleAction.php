<?php

namespace App\Actions;

use App\Models\Article;
use App\DataObjects\API\ArticleData;
use Illuminate\Support\Facades\Storage;
use App\Support\Actions\LoggedUserAction;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class UpsertArticleAction extends LoggedUserAction
{
    /**
     * Create a new Action instance.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $loggedUser
     * @param \App\Models\Article $repository
     */
    public function __construct(protected AuthenticatableContract $loggedUser,
                                protected Article $repository)
    {
    }

    /**
     * Execute the Action.
     *
     * @param \App\DataObjects\API\ArticleData $data
     * @param \App\Models\Article $articleToUpdate
     *
     * @return \App\Models\Article
     */
    public function execute(ArticleData $data, ?Article $articleToUpdate = null): Article
    {
        $attributesToFill = $data->except('image')->onlyFilled();

        if (! is_null($articleToUpdate)) {
            $article = $articleToUpdate->fill($attributesToFill);
        } else {
            $article = $this->repository
                            ->newInstance()
                            ->fill($attributesToFill);
        }

        $article->save();

        if (! is_null($image = $data->image)) {
            $imageName = ($article->slug . '-' . time() . '.' . $image->extension());

            Storage::disk('article-images')->delete($articleToUpdate?->image ?? '');

            $article->forceFill([
                'image' => Storage::disk('article-images')->putFileAs('/', $image, $imageName)
            ])->save();
        }

        $article->tags()->sync($data->tags->toCollection()->pluck('id'));

        return $article->load('category', 'tags');
    }
}
