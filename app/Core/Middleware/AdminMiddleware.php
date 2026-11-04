<?php

namespace App\Core\Middleware;

use App\Core\AdminAuth;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class AdminMiddleware
{
    private const OPEN_PATHS = ['/admin/login', '/admin/logout'];

    public static function handle(): void
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

        if (in_array($path, self::OPEN_PATHS, true)) {
            return;
        }

        if (!AdminAuth::check()) {
            redirect('/admin/login');
        }
    }
}
