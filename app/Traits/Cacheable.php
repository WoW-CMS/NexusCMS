<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;

trait Cacheable
{
    /**
     * Cache TTL in minutes
     */
    protected $cacheTTL = 60;

    /**
     * Log current cache driver
     */
    protected function logDriver()
    {
        $driver = config('cache.default');
        Log::debug("[Cacheable] Using cache driver: {$driver}");
        return $driver;
    }

    /**
     * Get cached model by ID
     */
    public function getCached($id)
    {
        $cacheKey = $this->getCacheKey($id);
        $driver = $this->logDriver();

        if (Cache::has($cacheKey)) {
            Log::debug("[Cacheable] HIT ({$driver}) key={$cacheKey}");
            return Cache::get($cacheKey);
        }

        Log::debug("[Cacheable] MISS ({$driver}) key={$cacheKey} → fetching from DB");
        $data = static::query()->find($id);
        Cache::put($cacheKey, $data, $this->cacheTTL * 60);

        return $data;
    }

    /**
     * Get cached model by a unique field (slug, email, etc.)
     */
    public function getCachedByField(string $field, $value)
    {
        $cacheKey = $this->getCacheKey("{$field}.{$value}");
        $driver = $this->logDriver();

        if (Cache::has($cacheKey)) {
            Log::debug("[Cacheable] HIT ({$driver}) key={$cacheKey}");
            return Cache::get($cacheKey);
        }

        Log::debug("[Cacheable] MISS ({$driver}) key={$cacheKey} → fetching from DB");
        $data = static::query()->where($field, $value)->first();
        Cache::put($cacheKey, $data, $this->cacheTTL * 60);

        return $data;
    }

    /**
     * Get cached list with query builder
     */
    public function getCachedList(Builder $query, ?int $perPage)
    {
        $query = $query ?: static::query();
        $perPage = $perPage ?: request()->get('per_page', 15);
        $cacheKey = $this->getListCacheKey($perPage, $query->toSql());
        $driver = $this->logDriver();

        if (Cache::has($cacheKey)) {
            Log::debug("[Cacheable] HIT ({$driver}) key={$cacheKey}");
            return Cache::get($cacheKey);
        }

        Log::debug("[Cacheable] MISS ({$driver}) key={$cacheKey} → fetching list from DB");
        $data = $query->paginate($perPage);
        Cache::put($cacheKey, $data, $this->cacheTTL * 60);

        return $data;
    }

    /**
     * Clear cache for this model
     */
    public function clearCache()
    {
        $driver = config('cache.default');
        Log::debug("[Cacheable] clearCache() for model=" . static::class . " id=" . ($this->id ?? 'null') . " driver={$driver}");

        if ($this->id) {
            $key = $this->getCacheKey($this->id);
            Cache::forget($key);
            Log::debug("[Cacheable] Cleared cache key={$key}");
        }

        $store = Cache::getStore();
        if (method_exists($store, 'tags')) {
            $tag = $this->getCacheTag();
            Cache::tags($tag)->flush();
            Log::debug("[Cacheable] Flushed cache tag={$tag}");
        }
    }

    /**
     * Boot the cacheable trait
     */
    protected static function bootCacheable()
    {
        static::saved(function ($model) {
            Log::debug("[Cacheable] bootCacheable() saved() triggered for " . static::class);
            $model->clearCache();
        });

        static::deleted(function ($model) {
            Log::debug("[Cacheable] bootCacheable() deleted() triggered for " . static::class);
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
