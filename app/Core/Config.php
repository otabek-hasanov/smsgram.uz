<?php

namespace App\Core;

use App\Config\AppConfig;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class Config
{
    private static ?array $items = null;

    private static function load(): array
    {
        return self::$items ??= AppConfig::all();
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $value = self::load();

        foreach (explode('.', $key) as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }
}
