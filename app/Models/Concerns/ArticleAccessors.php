<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait ArticleAccessors
{
    // /**
    //  * Retrieves the `title` attribute.
    //  *
    //  * @return \Illuminate\Database\Eloquent\Casts\Attribute
    //  */
    // protected function title(): Attribute
    // {
    //     return Attribute::make(
    //         get: function (string $title) {
    //             return strip_tags(html_entity_decode(preg_replace("/[\r\n]{2,}/", "\n\n", $title), ENT_QUOTES, 'UTF-8'));
    //         },
    //     );
    // }

    /**
     * Get the article's image url.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute|null
     */
    protected function imageUrl(): ?Attribute
    {
        return Attribute::make(
            get: function () {
                if (! empty($image = $this->image)) {
                    return Storage::disk('article-images')->url($image);
                }

                return null;
            },
        );
    }

    /**
     * Retrieves the `long_created` attribute.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function longCreated(): Attribute
    {
        return Attribute::make(get: fn () => $this->created_at->diffForHumans());
    }

    /**
     * Retrieves the `human_readable_created` attribute.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function humanReadableCreated(): Attribute
    {
        return Attribute::make(get: fn () => $this->created_at->translatedFormat('d F, Y'));
    }

    /**
     * Retrieves the `read_duration` attribute.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function readDuration(): Attribute
    {
        return Attribute::make(get: fn () => 'Leitura de ' . Str::readDuration($this->content). ' min');
    }

    /**
     * Retrieves the `url` attribute.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function url(): Attribute
    {
        return Attribute::make(get: fn () => route('web.posts.show', $this->slug));
    }

    /**
     * Retrieve the next article.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function next(): Attribute
    {
        return Attribute::make(get: function () {
            return $this->where($this->getQualifiedKeyName(), '>', $this->getKey())->oldest($this->getQualifiedKeyName())->first();
        });
    }

    /**
     * Retrieve the prev article
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function previous(): Attribute
    {
        return Attribute::make(get: function () {
            return $this->where($this->getQualifiedKeyName(), '<', $this->getKey())->latest($this->getQualifiedKeyName())->first();
        });
    }

    /**
     * Retrieves the edit link for the post on GitHub.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function githubUri(): Attribute
    {
        $url = 'https://github.com/allysonsilva/blog-posts/edit/';

        return Attribute::make(get: fn ($value) => $url . $value);
    }

    /**
     * Comma-separated article tags.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function commaTags(): Attribute
    {
        return Attribute::make(get: function () {
            return $this->tags->pluck('title')->implode(', ');
        });
    }
}
