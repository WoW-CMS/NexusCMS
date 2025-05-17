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

        return app('cache')->remember($cacheKey, $this->cacheTTL, function () use ($id) {
            return static::query()->find($id);
        });
    }

    /**
     * Get cached list with query builder
     */
    public function getCachedList($query = null, $perPage = null)
    {
        $query = $query ?: static::query();

        $perPage = $perPage ?: request()->get('per_page', 15);
        $cacheKey = $this->getListCacheKey($perPage, $query->toSql());

        return app('cache')->remember($cacheKey, $this->cacheTTL, function () use ($query, $perPage) {
            return $query->paginate($perPage);
        });
    }

    /**
     * Clear model cache
     */
    public function clearCache()
    {
        app('cache')->forget($this->getCacheKey($this->id));
        app('cache')->tags([$this->getCacheTag()])->flush();
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
     * Get cache key for a specific ID
     */
    protected function getCacheKey($id): string
    {
        return sprintf('%s.%s', $this->getCacheTag(), $id);
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
