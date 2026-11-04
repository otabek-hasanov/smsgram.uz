<?php

namespace App\Core;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        $cfg = Config::get('session');

        session_name($cfg['name']);
        session_set_cookie_params([
            'lifetime' => $cfg['lifetime'],
            'path'     => '/',
            'secure'   => $cfg['secure'],
            'httponly' => $cfg['httponly'],
            'samesite' => $cfg['samesite'],
        ]);

        session_start();
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function put(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function flash(string $key, mixed $value): void
    {
        $_SESSION['_flash'][$key] = $value;
    }

    public static function getFlash(string $key, mixed $default = null): mixed
    {
        $value = $_SESSION['_flash'][$key] ?? $default;
        unset($_SESSION['_flash'][$key]);

        return $value;
    }

    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }

    public static function destroy(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    public static function csrfToken(): string
    {
        if (!self::has('_csrf_token')) {
            self::put('_csrf_token', bin2hex(random_bytes(32)));
        }

        return self::get('_csrf_token');
    }

    public static function checkCsrf(?string $token): bool
    {
        return $token !== null && hash_equals(self::csrfToken(), $token);
    }
}
