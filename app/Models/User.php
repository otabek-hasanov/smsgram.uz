<?php

namespace App\Models;

use App\Core\Model;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class User extends Model
{
    protected static string $table = 'users';

    public static function create(array $data): int
    {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['role'] ??= 'user';
        $data['balance'] ??= 0;
        $data['status'] ??= 'active';

        return parent::create($data);
    }

    public static function emailExists(string $email): bool
    {
        return static::findBy('email', $email) !== null;
    }

    public static function incrementBalance(int $userId, float $amount): void
    {
        static::db()->run(
            'UPDATE `users` SET `balance` = `balance` + ? WHERE `id` = ?',
            [$amount, $userId]
        );
    }

    public static function decrementBalance(int $userId, float $amount): void
    {
        static::db()->run(
            'UPDATE `users` SET `balance` = `balance` - ? WHERE `id` = ?',
            [$amount, $userId]
        );
    }
}
