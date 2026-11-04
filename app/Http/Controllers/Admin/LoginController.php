<?php

namespace App\Http\Controllers\Admin;

use App\Core\AdminAuth;
use App\Core\Request;
use App\Core\Session;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class LoginController
{
    public static function show(): void
    {
        view('admin.auth.login', [
            'title' => 'Admin kirish' . ' — ' . 'Admin panel',
        ], 'layouts.admin-auth');
    }

    public static function login(): void
    {
        if (!Session::checkCsrf(Request::input('_token'))) {
            Session::flash('error', 'Sessiya muddati tugagan, qayta urinib ko\'ring.');
            redirect('/admin/login');
        }

        $username = trim((string) Request::input('username'));
        $password = (string) Request::input('password');

        if ($username === '' || $password === '') {
            Session::flash('error', 'Foydalanuvchi nomi va parolni kiriting.');
            redirect('/admin/login');
        }

        if (!AdminAuth::attempt($username, $password)) {
            Session::flash('error', 'Foydalanuvchi nomi yoki parol noto\'g\'ri.');
            redirect('/admin/login');
        }

        redirect('/admin');
    }

    public static function logout(): void
    {
        AdminAuth::logout();
        redirect('/admin/login');
    }
}
