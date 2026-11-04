<?php

namespace App\Http\Controllers\Auth;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class LoginController
{
    public static function show(): void
    {
        view('auth.login', [
            'title'       => __('auth.login_title') . ' — ' . __('common.app_name'),
            'description' => __('seo.login_desc'),
        ], 'layouts.auth');
    }

    public static function login(): void
    {
        if (!Session::checkCsrf(Request::input('_token'))) {
            Session::flash('error', 'Sessiya muddati tugagan, qayta urinib ko\'ring.');
            redirect(route('/login'));
        }

        $email = trim((string) Request::input('email'));
        $password = (string) Request::input('password');

        if ($email === '' || $password === '') {
            Session::flash('error', 'Email va parolni kiriting.');
            redirect(route('/login'));
        }

        if (!Auth::attempt($email, $password)) {
            Session::flash('error', 'Email yoki parol noto\'g\'ri.');
            redirect(route('/login'));
        }

        redirect(route('/dashboard'));
    }

    public static function logout(): void
    {
        Auth::logout();
        redirect(route('/login'));
    }
}
