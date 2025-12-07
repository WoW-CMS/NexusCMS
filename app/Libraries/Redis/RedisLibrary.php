<?php

namespace App\Libraries\Redis;

use Illuminate\Support\Facades\Redis;

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

    /**
     * RedisLibrary constructor.
     *
     * @param string $prefix Optional key prefix
     */
    public function __construct(string $prefix = '')
    {
        $this->prefix = $prefix;
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

    /* ======================================================
     * Basic Key/Value Methods
     * ====================================================== */

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

        if ($ttl > 0) {
            return Redis::setex($key, $ttl, json_encode($value));
        }

        return Redis::set($key, json_encode($value));
    }

    /**
     * Retrieve a value from Redis and decode it.
     *
     * @param string $key
     * @return mixed|null
     */
    public function get(string $key): mixed
    {
        $value = Redis::get($this->key($key));
        return $value ? json_decode($value, true) : null;
    }

    /**
     * Delete a Redis key.
     *
     * @param string $key
     * @return bool
     */
    public function delete(string $key): bool
    {
        return Redis::del($this->key($key)) > 0;
    }

    /**
     * Determine if a key exists.
     *
     * @param string $key
     * @return bool
     */
    public function exists(string $key): bool
    {
        return Redis::exists($this->key($key)) === 1;
    }

    /**
     * Get the TTL of a stored key.
     *
     * @param string $key
     * @return int
     */
    public function ttl(string $key): int
    {
        return Redis::ttl($this->key($key));
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
        return Redis::incrby($this->key($key), $amount);
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
        return Redis::decrby($this->key($key), $amount);
    }

    /* ======================================================
     * Token Management (JWT / Sessions)
     * ====================================================== */

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
