<?php

namespace App\Core;

use RuntimeException;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class View
{
    public static function render(string $view, array $data = [], ?string $layout = null): string
    {
        $viewFile = Paths::views() . '/' . str_replace('.', '/', $view) . '.php';

        if (!is_file($viewFile)) {
            throw new RuntimeException("View topilmadi: {$view}");
        }

        $content = self::capture($viewFile, $data);

        if ($layout !== null) {
            $layoutFile = Paths::views() . '/' . str_replace('.', '/', $layout) . '.php';

            if (!is_file($layoutFile)) {
                throw new RuntimeException("Layout topilmadi: {$layout}");
            }

            $content = self::capture($layoutFile, [...$data, 'content' => $content]);
        }

        return $content;
    }

    private static function capture(string $file, array $data): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        include $file;

        return ob_get_clean();
    }

    public static function e(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}
