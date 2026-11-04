<?php

namespace App\Core;

use App\Core\Cache\PredisCache;

defined('SMSGRAM') or die('Direct access is not allowed.');

/**
 * Sodda fasad: Cache::remember('key', 60, fn() => ...)
 */
final class Cache
{
    private static ?PredisCache $provider = null;

    private static function provider(): PredisCache
    {
        if (self::$provider === null) {
            self::$provider = new PredisCache(Redis::getInstance());
            self::$provider->setNamespace('smsgram');
        }

        return self::$provider;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $value = self::provider()->fetch($key);

        return $value === false ? $default : $value;
    }

    public static function put(string $key, mixed $value, int $ttlSeconds = 0): bool
    {
        return self::provider()->save($key, $value, $ttlSeconds);
    }

    public static function has(string $key): bool
    {
        return self::provider()->contains($key);
    }

    public static function forget(string $key): bool
    {
        return self::provider()->delete($key);
    }

    public static function remember(string $key, int $ttlSeconds, callable $callback): mixed
    {
        if (self::has($key)) {
            return self::get($key);
        }

        $value = $callback();
        self::put($key, $value, $ttlSeconds);

        return $value;
    }

    public static function flush(): bool
    {
        return self::provider()->flushAll();
    }
}
