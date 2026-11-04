<?php

namespace App\Core\Cache;

use Doctrine\Common\Cache\CacheProvider;
use Predis\Client;

defined('SMSGRAM') or die('Direct access is not allowed.');

/**
 * doctrine/cache ning CacheProvider abstraksiyasini Predis (Redis) orqali
 * amalga oshiradi — saytni maksimal tezkor qilish uchun keshlash shu yerdan boshqariladi.
 */
final class PredisCache extends CacheProvider
{
    public function __construct(private readonly Client $redis)
    {
    }

    protected function doFetch($id): mixed
    {
        $value = $this->redis->get($id);

        return $value === null ? false : unserialize($value);
    }

    protected function doContains($id): bool
    {
        return (bool) $this->redis->exists($id);
    }

    protected function doSave($id, $data, $lifeTime = 0): bool
    {
        $serialized = serialize($data);

        if ($lifeTime > 0) {
            $this->redis->setex($id, $lifeTime, $serialized);
        } else {
            $this->redis->set($id, $serialized);
        }

        return true;
    }

    protected function doDelete($id): bool
    {
        $this->redis->del([$id]);

        return true;
    }

    protected function doFlush(): bool
    {
        $this->redis->flushdb();

        return true;
    }

    protected function doGetStats(): ?array
    {
        return null;
    }
}
