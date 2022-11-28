<?php

namespace App\Models;

use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use Spatie\Sitemap\Tags\Url;
use Spatie\Sluggable\HasSlug;
use App\Support\ORM\BaseModel;
use Spatie\Sluggable\SlugOptions;
use App\Models\Concerns\ArticleScopes;
use App\Models\Concerns\ArticleAccessors;
use Spatie\Sitemap\Contracts\Sitemapable;
use Illuminate\Database\Eloquent\Collection;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Boots\ForArticle as BootForArticle;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends BaseModel implements Sitemapable, Feedable
{
    use HasSlug;
    use HasUuids;
    use SoftDeletes;
    use ArticleScopes;
    use BootForArticle;
    use ArticleAccessors;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'image',
        'github_uri',
        'likes',
        'content',
        'summary',
        'visits',
        'color',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'next',
        'previous',
        'image_url',
        'comma_tags',
    ];

    /**
     * The relationships that should be touched on save.
     *
     * @var array
     */
    protected $touches = ['category'];

    /**
     * Get the options for generating the slug.
     *
     * @return \Spatie\Sluggable\SlugOptions
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->skipGenerateWhen(fn () => ! empty($this->slug))
            ->startSlugSuffixFrom(2)
            ->doNotGenerateSlugsOnUpdate();
    }

    /**
     * @return \Spatie\Sitemap\Tags\Url|string|array
     */
    public function toSitemapTag(): Url|string|array
    {
        return Url::create($this->url)
                    ->setLastModificationDate($this->updated_at);
    }

    /**
     * Define how to construct the RSS feed items based on the model properties
     *
     * @return \Spatie\Feed\FeedItem
     */
    public function toFeedItem(): FeedItem
    {
        return FeedItem::create()
                        ->id($this->slug)
                        ->title($this->title)
                        ->summary(Markdown::convert($this->summary)->getContent())
                        ->updated($this->updated_at)
                        ->link($this->url)
                        ->authorName('Alyson Silva');
    }

    /**
     * Fetch data to be included in the RSS feed.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getFeedItems(): Collection
    {
        return self::all();
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Article tags.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tag');
    }

    /**
     * Article category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
