<?php

namespace App\Core\Middleware;

use App\Core\Auth;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class GuestMiddleware
{
    public static function handle(): void
    {
        if (Auth::check()) {
            redirect(route('/dashboard'));
        }
    }
}
