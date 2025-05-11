<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;

trait Cacheable
{
    /**
     * Cache TTL in minutes
     */
    protected static $cacheTTL = 60;

    /**
     * Get cached model by ID
     */
    public static function getCached($id)
    {
        $cacheKey = static::getCacheKey($id);
        
        return Cache::remember($cacheKey, static::$cacheTTL, function () use ($id) {
            return static::query()->find($id);
        });
    }

    /**
     * Get cached list with query builder
     */
    public static function getCachedList($query = null)
    {
        $query = $query ?: static::query();
        $perPage = request()->get('per_page', 15);
        $cacheKey = static::getListCacheKey($perPage, $query->toSql());
        
        return Cache::remember($cacheKey, static::$cacheTTL, function () use ($query, $perPage) {
            return $query->paginate($perPage);
        });
    }

    /**
     * Clear model cache
     */
    public function clearCache()
    {
        Cache::forget(static::getCacheKey($this->id));
        Cache::tags([static::getCacheTag()])->flush();
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
    protected static function getCacheKey($id): string
    {
        return sprintf('%s.%s', static::getCacheTag(), $id);
    }

    /**
     * Get cache key for list
     */
    protected static function getListCacheKey($perPage, $query): string
    {
        return sprintf('%s.list.%s.%s.%s', 
            static::getCacheTag(), 
            $perPage,
            md5($query),
            request()->page ?? 1
        );
    }

    /**
     * Get cache tag based on model class
     */
    protected static function getCacheTag(): string
    {
        return strtolower(class_basename(static::class));
    }
}