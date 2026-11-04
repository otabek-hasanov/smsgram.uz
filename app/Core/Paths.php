<?php

namespace App\Core;

defined('SMSGRAM') or die('Direct access is not allowed.');

/**
 * Loyihaning barcha yo'llari shu klass orqali aniqlanadi
 * (const BASE_PATH/APP_PATH/... kabi global konstantalar o'rniga).
 */
final class Paths
{
    private static ?string $base = null;

    public static function base(): string
    {
        return self::$base ??= dirname(__DIR__, 2);
    }

    public static function app(): string
    {
        return self::base() . '/app';
    }

    public static function views(): string
    {
        return self::base() . '/resources/views';
    }

    public static function storage(): string
    {
        return self::base() . '/storage';
    }
}
