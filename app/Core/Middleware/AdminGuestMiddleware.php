<?php

namespace App\Core\Middleware;

use App\Core\AdminAuth;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class AdminGuestMiddleware
{
    public static function handle(): void
    {
        if (AdminAuth::check()) {
            redirect('/admin');
        }
    }
}
