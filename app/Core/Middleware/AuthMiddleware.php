<?php

namespace App\Core\Middleware;

use App\Core\Auth;
use App\Core\Session;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class AuthMiddleware
{
    public static function handle(): void
    {
        if (!Auth::check()) {
            Session::flash('error', 'Davom etish uchun tizimga kiring.');
            redirect(route('/login'));
        }
    }
}
