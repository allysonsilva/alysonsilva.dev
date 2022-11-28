<?php

namespace App\Models\Boots;

use App\Models\Guest;
use App\Events\NewArticleCreated;
use App\Notifications\NewArticle;
use Illuminate\Support\Facades\Notification;

trait ForArticle
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function bootForArticle()
    {
        static::created(function (self $createdArticle) {
            Notification::send(Guest::all(), new NewArticle($createdArticle));
            // NewArticleCreated::dispatch($createdArticle);
        });
    }
}
