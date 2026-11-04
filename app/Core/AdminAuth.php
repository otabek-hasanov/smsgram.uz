<?php

namespace App\Core;

use App\Models\Admin;

defined('SMSGRAM') or die('Direct access is not allowed.');

/**
 * Admin akkauntlari uchun butunlay mustaqil autentifikatsiya tizimi.
 * Oddiy foydalanuvchi Auth klassi bilan hech qanday bog'liqligi yo'q —
 * alohida sessiya kaliti, alohida jadval (`admins`), alohida login sahifasi.
 */
final class AdminAuth
{
    private const SESSION_KEY = 'admin_auth_id';

    private static ?array $cachedAdmin = null;

    public static function attempt(string $username, string $password): bool
    {
        $admin = Admin::findByUsername($username);

        if (!$admin || !password_verify($password, $admin['password'])) {
            return false;
        }

        self::login($admin);

        return true;
    }

    public static function login(array $admin): void
    {
        Session::regenerate();
        Session::put(self::SESSION_KEY, $admin['id']);
        self::$cachedAdmin = $admin;
    }

    public static function logout(): void
    {
        Session::forget(self::SESSION_KEY);
        self::$cachedAdmin = null;
        Session::regenerate();
    }

    public static function check(): bool
    {
        return self::admin() !== null;
    }

    public static function id(): ?int
    {
        return self::admin()['id'] ?? null;
    }

    public static function admin(): ?array
    {
        if (self::$cachedAdmin !== null) {
            return self::$cachedAdmin;
        }

        $id = Session::get(self::SESSION_KEY);

        if (!$id) {
            return null;
        }

        $admin = Admin::find((int) $id);

        if (!$admin) {
            return null;
        }

        self::$cachedAdmin = $admin;

        return $admin;
    }
}
