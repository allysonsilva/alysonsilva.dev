<?php

namespace App\Models;

use Spatie\Sitemap\Tags\Url;
use Spatie\Sluggable\HasSlug;
use App\Support\ORM\BaseModel;
use Spatie\Sluggable\SlugOptions;
use App\Models\Concerns\CategoryScopes;
use Spatie\Sitemap\Contracts\Sitemapable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends BaseModel implements Sitemapable
{
    use HasSlug;
    use HasUuids;
    use SoftDeletes;
    use CategoryScopes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'icon',
        'color',
        'order',
        'must_be_menu',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['icon_url', 'icon_class'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'must_be_menu' => 'boolean',
    ];

    /**
     * only categories that should be listed in the website menu.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return void
     */
    public function scopeMustBeMenu(Builder $query): void
    {
        $query->where('must_be_menu', true);
    }

    /**
     * Interact with the `icon_url`.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function iconUrl(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                if (empty($icon = $attributes['icon'] ?? null)) return null;

                if (str_starts_with($icon, 'class')) return null;

                return mix('/storage/images/icons/' . $attributes['icon']);
            },
        );
    }

    /**
     * Interact with the `icon_class`.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function iconClass(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                if (empty($icon = $attributes['icon'] ?? null)) return null;

                if (str_starts_with($icon, 'class')) {
                    return (explode(':', $icon)[1]) . ' colored';
                }

                return null;
            },
        );
    }

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
                ->doNotGenerateSlugsOnUpdate();
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
     * @return \Spatie\Sitemap\Tags\Url|string|array
     */
    public function toSitemapTag(): Url|string|array
    {
        return Url::create(route('web.blog.category-posts.show', $this))
                    ->setLastModificationDate($this->updated_at);
    }

    /**
     * Retrieve articles from the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function hasImage(): bool
    {
        return ! is_null($this->icon_url);
    }

    public function hasCssClass(): bool
    {
        return ! is_null($this->icon_class);
    }

    /**
     * Category devicon icon.
     *
     * @return string|null
     */
    public function deviconClass(): ?string
    {
        if ($this->hasCssClass()) {
            return $this->icon_class;
        }

        return null;
    }
}
