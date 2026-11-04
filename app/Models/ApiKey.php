<?php

namespace App\Models;

use App\Core\Model;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class ApiKey extends Model
{
    protected static string $table = 'api_keys';

    public static function generateFor(int $userId, string $name = 'Default key'): array
    {
        $key = 'sg_' . bin2hex(random_bytes(24));

        $id = static::create([
            'user_id'   => $userId,
            'name'      => $name,
            'key_value' => $key,
        ]);

        return ['id' => $id, 'key_value' => $key];
    }

    public static function findByKey(string $key): ?array
    {
        return static::findBy('key_value', $key);
    }

    public static function touch(int $id): void
    {
        static::db()->run('UPDATE `api_keys` SET `last_used_at` = NOW() WHERE `id` = ?', [$id]);
    }
}
