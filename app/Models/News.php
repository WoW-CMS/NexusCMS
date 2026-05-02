<?php

namespace App\Models;

use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use HasFactory;
    use Cacheable;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'author_id',
        'category_id',
        'is_published',
        'published_at',
        'title_translations',
        'content_translations',
        'excerpt',
    ];

    protected $casts = [
        'published_at'          => 'datetime',
        'is_published'          => 'boolean',
        'title_translations'    => 'array',
        'content_translations'  => 'array',
    ];

    /**
     * Always return published_at as Carbon (handles int timestamps from cache deserialization).
     */
    public function getPublishedAtAttribute($value): ?\Carbon\Carbon
    {
        if ($value === null) return null;
        if ($value instanceof \Carbon\Carbon) return $value;
        return \Carbon\Carbon::parse($value);
    }

    /**
     * Always return created_at as Carbon.
     */
    public function getCreatedAtAttribute($value): ?\Carbon\Carbon
    {
        if ($value === null) return null;
        if ($value instanceof \Carbon\Carbon) return $value;
        return \Carbon\Carbon::parse($value);
    }

    /**
     * Always return updated_at as Carbon.
     */
    public function getUpdatedAtAttribute($value): ?\Carbon\Carbon
    {
        if ($value === null) return null;
        if ($value instanceof \Carbon\Carbon) return $value;
        return \Carbon\Carbon::parse($value);
    }

    /**
     * Get a translated field value with locale fallback.
     *
     * Resolution order:
     *  1. Requested locale in *_translations JSON
     *  2. Default/fallback locale in *_translations JSON
     *  3. Legacy plain-text field (title / content)
     *  4. Empty string
     */
    public function getTranslated(string $field, ?string $locale = null, ?string $fallback = null): string
    {
        $locale   = $locale   ?? app()->getLocale();
        $fallback = $fallback ?? config('app.fallback_locale', 'en');

        $translationsField = $field . '_translations';

        // Only try translations if the column exists and is populated
        if (isset($this->$translationsField) && is_array($this->$translationsField)) {
            $translations = $this->$translationsField;

            // Use !empty() so that empty-string translations also fall through
            if (!empty($translations[$locale])) {
                return $translations[$locale];
            }
            if (!empty($translations[$fallback])) {
                return $translations[$fallback];
            }
        }

        return (string) ($this->$field ?? '');
    }

    /**
     * Convenience: translated title.
     */
    public function translatedTitle(?string $locale = null): string
    {
        return $this->getTranslated('title', $locale);
    }

    /**
     * Convenience: translated content.
     */
    public function translatedContent(?string $locale = null): string
    {
        return $this->getTranslated('content', $locale);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(NewsCategory::class, 'category_id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Override clearCache to also invalidate the slug-based cache key
     * used by getCachedByField('slug', ...) and bust all list caches
     * by incrementing the list version counter.
     */
    public function clearCache()
    {
        // Replicate Cacheable trait logic (trait methods cannot use parent::)
        if ($this->id) {
            \Illuminate\Support\Facades\Cache::forget($this->getCacheKey($this->id));
        }

        $store = \Illuminate\Support\Facades\Cache::getStore();
        if (method_exists($store, 'tags')) {
            \Illuminate\Support\Facades\Cache::tags($this->getCacheTag())->flush();
        }

        // Clear slug-based cache key used by getCachedByField('slug', ...)
        if ($this->slug) {
            \Illuminate\Support\Facades\Cache::forget(
                $this->getCacheKey("slug.{$this->slug}")
            );
        }

        // Bust all list caches by incrementing the version counter
        \Illuminate\Support\Facades\Cache::increment($this->getCacheTag() . '.list.version');
    }

    public function relatedContents($limit = 2)
    {
        return static::query()
            ->where('id', '!=', $this->id)
            ->where('is_published', 1)
            ->when($this->category_id, function ($query) {
                $query->where('category_id', $this->category_id);
            })
            ->orderBy('published_at', 'desc')
            ->take($limit)
            ->get();
    }

    public function recentNews($limit = 3)
    {
        return static::query()
            ->where('is_published', 1)
            ->orderBy('published_at', 'desc')
            ->take($limit)
            ->get();
    }
}
