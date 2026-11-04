<?php

use App\Core\Auth;
use App\Core\Icons;
use App\Core\Paths;
use App\Core\Session;
use App\Core\Translator;
use App\Core\View;

defined('SMSGRAM') or die('Direct access is not allowed.');

/*
|--------------------------------------------------------------------------
| Global helper funksiyalar
|--------------------------------------------------------------------------
| Kontrollerlar va view fayllari namespace bilan chalkashmasligi uchun
| ko'p ishlatiladigan amallar shu yerda global funksiya sifatida
| e'lon qilinadi ($this->view() o'rniga oddiy view() va h.k).
*/

if (!function_exists('e')) {
    function e(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('icon')) {
    function icon(string $name, string $class = 'w-5 h-5'): string
    {
        return Icons::svg($name, $class);
    }
}

if (!function_exists('logo_mark')) {
    function logo_mark(string $class = 'w-4 h-4'): string
    {
        return Icons::mark($class);
    }
}

if (!function_exists('__')) {
    function __(string $key, array $replace = []): string
    {
        return Translator::get($key, $replace);
    }
}

if (!function_exists('current_locale')) {
    function current_locale(): string
    {
        return Translator::locale();
    }
}

if (!function_exists('route')) {
    /**
     * Foydalanuvchiga ochiq (public/user panel) sahifalar uchun joriy til
     * prefiksi bilan yo'l quradi: route('/dashboard') -> /uz/dashboard.
     * Admin panelda ishlatilmaydi — u til prefiksisiz /admin'da qoladi.
     */
    function route(string $path = ''): string
    {
        return '/' . current_locale() . $path;
    }
}

if (!function_exists('view')) {
    function view(string $view, array $data = [], ?string $layout = null): void
    {
        echo View::render($view, $data, $layout);
    }
}

if (!function_exists('redirect')) {
    function redirect(string $to): never
    {
        header('Location: ' . $to);
        exit;
    }
}

if (!function_exists('back')) {
    function back(): never
    {
        redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }
}

if (!function_exists('json_response')) {
    function json_response(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}

if (!function_exists('abort_with')) {
    function abort_with(int $status, string $message = ''): never
    {
        http_response_code($status);
        view('errors.' . $status, ['message' => $message]);
        exit;
    }
}

if (!function_exists('auth_user')) {
    function auth_user(): ?array
    {
        return Auth::user();
    }
}

if (!function_exists('auth_check')) {
    function auth_check(): bool
    {
        return Auth::check();
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        return Session::csrfToken();
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
    }
}

if (!function_exists('old')) {
    function old(string $key, string $default = ''): string
    {
        static $flashed = null;

        if ($flashed === null) {
            $flashed = Session::getFlash('old', []);
        }

        return e($flashed[$key] ?? $default);
    }
}

if (!function_exists('flash')) {
    function flash(string $key): ?string
    {
        return Session::getFlash($key);
    }
}

if (!function_exists('money')) {
    function money(float $amount): string
    {
        return number_format($amount, 0, '.', ' ') . " so'm";
    }
}

if (!function_exists('asset_url')) {
    function asset_url(string $path): string
    {
        $file = Paths::base() . '/assets/' . ltrim($path, '/');
        $version = is_file($file) ? filemtime($file) : time();

        return '/assets/' . ltrim($path, '/') . '?v=' . $version;
    }
}

if (!function_exists('sms_status_badge')) {
    function sms_status_badge(string $status): string
    {
        return match ($status) {
            'delivered', 'sent' => 'badge-green',
            'pending' => 'badge-yellow',
            'failed' => 'badge-red',
            default => 'badge',
        };
    }
}
