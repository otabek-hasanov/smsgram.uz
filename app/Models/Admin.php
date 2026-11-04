<?php

namespace App\Models;

use App\Core\Model;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class Admin extends Model
{
    protected static string $table = 'admins';

    public static function findByUsername(string $username): ?array
    {
        return static::findBy('username', $username);
    }

    public static function usernameExists(string $username): bool
    {
        return static::findByUsername($username) !== null;
    }
}
