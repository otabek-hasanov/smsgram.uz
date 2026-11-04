<?php

namespace App\Core;

use App\Models\User;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class Auth
{
    private const SESSION_KEY = 'auth_user_id';

    private static ?array $cachedUser = null;

    public static function attempt(string $email, string $password): bool
    {
        $user = User::findBy('email', $email);

        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        if ($user['status'] !== 'active') {
            return false;
        }

        self::login($user);

        return true;
    }

    public static function login(array $user): void
    {
        Session::regenerate();
        Session::put(self::SESSION_KEY, $user['id']);
        self::$cachedUser = $user;
    }

    public static function logout(): void
    {
        Session::forget(self::SESSION_KEY);
        self::$cachedUser = null;
        Session::regenerate();
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function id(): ?int
    {
        return self::user()['id'] ?? null;
    }

    public static function user(): ?array
    {
        if (self::$cachedUser !== null) {
            return self::$cachedUser;
        }

        $id = Session::get(self::SESSION_KEY);

        if (!$id) {
            return null;
        }

        $user = User::find((int) $id);

        if (!$user || $user['status'] !== 'active') {
            return null;
        }

        self::$cachedUser = $user;

        return $user;
    }
}
