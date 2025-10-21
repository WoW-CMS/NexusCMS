<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;

trait Cacheable
{
    /**
     * Cache TTL in minutes
     */
    protected $cacheTTL = 60;

    /**
     * Get cached model by ID
     */
    public function getCached($id)
    {
        $cacheKey = $this->getCacheKey($id);

        return Cache::remember($cacheKey, $this->cacheTTL * 60, function () use ($id) {
            return static::query()->find($id);
        });
    }

    /**
     * Get cached model by a unique field (slug, email, etc.)
     */
    public function getCachedByField(string $field, $value)
    {
        $cacheKey = $this->getCacheKey("{$field}.{$value}");

        return Cache::remember($cacheKey, $this->cacheTTL * 60, function () use ($field, $value) {
            return static::query()->where($field, $value)->first();
        });
    }

    /**
     * Get cached list with query builder
     */
    public function getCachedList(Builder $query, ?int $perPage)
    {
        $query = $query ?: static::query();
        $perPage = $perPage ?: request()->get('per_page', 15);

        $cacheKey = $this->getListCacheKey($perPage, $query->toSql());

        return Cache::remember($cacheKey, $this->cacheTTL * 60, function () use ($query, $perPage) {
            return $query->paginate($perPage);
        });
    }

    /**
     * Clear cache for this model
     */
    public function clearCache()
    {
        // Clear ID-based cache
        if ($this->id) {
            Cache::forget($this->getCacheKey($this->id));
        }

        // Clear all tagged cache only if driver supports it
        $store = Cache::getStore();
        if (method_exists($store, 'tags')) {
            Cache::tags($this->getCacheTag())->flush();
        }
    }

    /**
     * Boot the cacheable trait
     */
    protected static function bootCacheable()
    {
        static::saved(function ($model) {
            $model->clearCache();
        });

        static::deleted(function ($model) {
            $model->clearCache();
        });
    }

    /**
     * Get cache key for a specific identifier (ID, slug, etc.)
     */
    protected function getCacheKey($identifier): string
    {
        return sprintf('%s.%s', $this->getCacheTag(), $identifier);
    }

    /**
     * Get cache key for list
     */
    protected function getListCacheKey($perPage, $query): string
    {
        return sprintf(
            '%s.list.%s.%s.%s',
            $this->getCacheTag(),
            $perPage,
            md5($query),
            request()->page ?? 1
        );
    }

    /**
     * Get cache tag based on model class
     */
    protected function getCacheTag(): string
    {
        return strtolower(class_basename(static::class));
    }
}
