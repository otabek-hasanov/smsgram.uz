<?php

namespace App\Models;

use App\Core\Cache;
use App\Core\Database;

defined('SMSGRAM') or die('Direct access is not allowed.');

/**
 * Admin panelda sozlanadigan kalit-qiymat sozlamalar (masalan shablon narxi).
 * O'qishlar Redis'da keshlanadi — tez-tez o'qiladigan sozlamalar uchun
 * har safar bazaga murojaat qilinmasligi kerak.
 */
final class Setting
{
    private const CACHE_TTL = 300;

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("setting:{$key}", self::CACHE_TTL, static function () use ($key, $default) {
            $row = Database::getInstance()->selectOne(
                'SELECT setting_value FROM `app_settings` WHERE `setting_key` = ?',
                [$key]
            );

            return $row['setting_value'] ?? $default;
        });
    }

    public static function set(string $key, string $value): void
    {
        $db = Database::getInstance();
        $exists = $db->selectOne('SELECT setting_key FROM `app_settings` WHERE `setting_key` = ?', [$key]);

        if ($exists) {
            $db->update('app_settings', ['setting_value' => $value], '`setting_key` = ?', [$key]);
        } else {
            $db->insert('app_settings', ['setting_key' => $key, 'setting_value' => $value]);
        }

        Cache::forget("setting:{$key}");
    }

    public static function all(): array
    {
        return Database::getInstance()->select('SELECT * FROM `app_settings` ORDER BY `setting_key` ASC');
    }
}
