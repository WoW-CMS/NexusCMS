<?php

namespace App\Libraries\Redis;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;

/**
 * Class RedisLibrary
 *
 * A Laravel-friendly Redis wrapper providing cached key-value operations,
 * TTL helpers, increment/decrement utilities, and token management.
 */
class RedisLibrary
{
    /** @var string Global key prefix */
    private string $prefix;
    
    /** @var bool Whether Redis operations are enabled */
    private bool $enabled;

    /**
     * RedisLibrary constructor.
     *
     * @param string $prefix Optional key prefix
     */
    public function __construct(string $prefix = '')
    {
        $this->prefix = $prefix;
        // Read flag from config (safe with config:cache in production).
        $this->enabled = (bool) config('nexus.redis_enabled', false);
    }

    /**
     * Encode mixed payloads so objects/collections survive round trips.
     */
    private function encodeValue(mixed $value): string
    {
        return base64_encode(serialize($value));
    }

    /**
     * Decode payloads with backward compatibility for older JSON/string values.
     */
    private function decodeValue(?string $value): mixed
    {
        if ($value === null) {
            return null;
        }

        $decoded = base64_decode($value, true);
        if ($decoded !== false) {
            $unserialized = @unserialize($decoded, ['allowed_classes' => true]);

            if ($unserialized !== false || $decoded === serialize(false)) {
                return $unserialized;
            }
        }

        $json = json_decode($value, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $json;
        }

        return $value;
    }

    /**
     * Build a namespaced key.
     *
     * @param string $key
     * @return string
     */
    private function key(string $key): string
    {
        return $this->prefix . $key;
    }

    /**
     * Store a value in Redis.
     *
     * @param string $key
     * @param mixed  $value
     * @param int    $ttl  Expiration time in seconds (0 = persistent)
     *
     * @return bool
     */
    public function set(string $key, mixed $value, int $ttl = 0): bool
    {
        $key = $this->key($key);
        $payload = $this->encodeValue($value);

        if (!$this->enabled) {
            if ($ttl > 0) {
                Cache::put($key, $value, now()->addSeconds($ttl));
            } else {
                Cache::forever($key, $value);
            }
            return true;
        }

        try {
            if ($ttl > 0) {
                return (bool) Redis::setex($key, $ttl, $payload);
            }

            return (bool) Redis::set($key, $payload);
        } catch (\Throwable $e) {
            if ($ttl > 0) {
                Cache::put($key, $value, now()->addSeconds($ttl));
            } else {
                Cache::forever($key, $value);
            }
            return true;
        }
    }

    /**
     * Retrieve a value from Redis and decode it.
     *
     * @param string $key
     * @return mixed|null
     */
    public function get(string $key): mixed
    {
        $key = $this->key($key);

        if (!$this->enabled) {
            return Cache::get($key, null);
        }

        try {
            $value = Redis::get($key);
            return $this->decodeValue($value);
        } catch (\Throwable $e) {
            return Cache::get($key, null);
        }
    }

    /**
     * Delete a Redis key.
     *
     * @param string $key
     * @return bool
     */
    public function delete(string $key): bool
    {
        $key = $this->key($key);
        if (!$this->enabled) {
            return Cache::forget($key);
        }
        try {
            return Redis::del($key) > 0;
        } catch (\Throwable $e) {
            return Cache::forget($key);
        }
    }

    /**
     * Determine if a key exists.
     *
     * @param string $key
     * @return bool
     */
    public function exists(string $key): bool
    {
        $key = $this->key($key);
        if (!$this->enabled) {
            return Cache::has($key);
        }
        try {
            return Redis::exists($key) === 1;
        } catch (\Throwable $e) {
            return Cache::has($key);
        }
    }

    /**
     * Get the TTL of a stored key.
     *
     * @param string $key
     * @return int
     */
    public function ttl(string $key): int
    {
        $key = $this->key($key);
        if (!$this->enabled) {
            return -1; // TTL no disponible en fallback de Cache
        }
        try {
            return Redis::ttl($key);
        } catch (\Throwable $e) {
            return -1;
        }
    }

    /**
     * Increment a stored numeric key.
     *
     * @param string $key
     * @param int    $amount
     * @return int
     */
    public function increment(string $key, int $amount = 1): int
    {
        $key = $this->key($key);
        if (!$this->enabled) {
            $current = (int) (Cache::get($key, 0) ?? 0);
            $new = $current + $amount;
            Cache::put($key, $new);
            return $new;
        }
        try {
            return Redis::incrby($key, $amount);
        } catch (\Throwable $e) {
            $current = (int) (Cache::get($key, 0) ?? 0);
            $new = $current + $amount;
            Cache::put($key, $new);
            return $new;
        }
    }

    /**
     * Decrement a stored numeric key.
     *
     * @param string $key
     * @param int    $amount
     * @return int
     */
    public function decrement(string $key, int $amount = 1): int
    {
        $key = $this->key($key);
        if (!$this->enabled) {
            $current = (int) (Cache::get($key, 0) ?? 0);
            $new = $current - $amount;
            Cache::put($key, $new);
            return $new;
        }
        try {
            return Redis::decrby($key, $amount);
        } catch (\Throwable $e) {
            $current = (int) (Cache::get($key, 0) ?? 0);
            $new = $current - $amount;
            Cache::put($key, $new);
            return $new;
        }
    }

    /**
     * Store a user token using a namespaced key.
     *
     * @param string $userId
     * @param string $token
     * @param int    $ttl
     * @return bool
     */
    public function storeToken(string $userId, string $token, int $ttl): bool
    {
        return $this->set("user:{$userId}:token", $token, $ttl);
    }

    /**
     * Retrieve a stored user token.
     *
     * @param string $userId
     * @return string|null
     */
    public function getToken(string $userId): ?string
    {
        return $this->get("user:{$userId}:token");
    }

    /**
     * Delete a stored user token.
     *
     * @param string $userId
     * @return bool
     */
    public function deleteToken(string $userId): bool
    {
        return $this->delete("user:{$userId}:token");
    }
}
