<?php

namespace App\Core;

use App\Lang\En;
use App\Lang\Ru;
use App\Lang\Uz;

defined('SMSGRAM') or die('Direct access is not allowed.');

/**
 * uz/ru/en tarjima tizimi. Tanlangan til sessiyada saqlanadi.
 */
final class Translator
{
    private const LOCALE_CLASSES = [
        'uz' => Uz::class,
        'ru' => Ru::class,
        'en' => En::class,
    ];

    private static ?string $locale = null;
    private static array $cache = [];

    public static function init(): void
    {
        $locale = Session::get('locale');

        self::$locale = self::isSupported((string) $locale)
            ? $locale
            : Config::get('app.default_locale', 'uz');
    }

    public static function locale(): string
    {
        return self::$locale ??= Config::get('app.default_locale', 'uz');
    }

    public static function isSupported(string $locale): bool
    {
        return in_array($locale, Config::get('app.locales', ['uz']), true);
    }

    public static function setLocale(string $locale): bool
    {
        if (!self::isSupported($locale)) {
            return false;
        }

        Session::put('locale', $locale);
        self::$locale = $locale;

        return true;
    }

    public static function get(string $key, array $replace = []): string
    {
        $lines = self::load(self::locale());
        $value = $lines;

        foreach (explode('.', $key) as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $key;
            }
            $value = $value[$segment];
        }

        if (!is_string($value)) {
            return $key;
        }

        foreach ($replace as $name => $replacement) {
            $value = str_replace(':' . $name, (string) $replacement, $value);
        }

        return $value;
    }

    private static function load(string $locale): array
    {
        if (!isset(self::$cache[$locale])) {
            $class = self::LOCALE_CLASSES[$locale] ?? self::LOCALE_CLASSES['uz'];
            self::$cache[$locale] = $class::all();
        }

        return self::$cache[$locale];
    }
}
