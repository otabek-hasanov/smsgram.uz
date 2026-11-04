<?php

namespace App\Http\Controllers\Auth;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;
use App\Models\User;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class RegisterController
{
    public static function show(): void
    {
        view('auth.register', [
            'title'       => __('auth.register_title') . ' — ' . __('common.app_name'),
            'description' => __('seo.register_desc'),
        ], 'layouts.auth');
    }

    public static function register(): void
    {
        if (!Session::checkCsrf(Request::input('_token'))) {
            Session::flash('error', 'Sessiya muddati tugagan, qayta urinib ko\'ring.');
            redirect(route('/register'));
        }

        $name = trim((string) Request::input('name'));
        $email = trim((string) Request::input('email'));
        $phone = trim((string) Request::input('phone'));
        $password = (string) Request::input('password');
        $passwordConfirm = (string) Request::input('password_confirmation');

        $errors = [];

        if ($name === '') {
            $errors[] = 'Ism kiritilishi shart.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email manzil noto\'g\'ri.';
        } elseif (User::emailExists($email)) {
            $errors[] = 'Bu email allaqachon ro\'yxatdan o\'tgan.';
        }
        if (strlen($password) < 6) {
            $errors[] = 'Parol kamida 6 belgidan iborat bo\'lishi kerak.';
        } elseif ($password !== $passwordConfirm) {
            $errors[] = 'Parollar mos kelmadi.';
        }

        if (!empty($errors)) {
            Session::flash('error', implode(' ', $errors));
            Session::flash('old', ['name' => $name, 'email' => $email, 'phone' => $phone]);
            redirect(route('/register'));
        }

        $userId = User::create([
            'name'     => $name,
            'email'    => $email,
            'phone'    => $phone ?: null,
            'password' => $password,
        ]);

        Auth::login(User::find($userId));

        redirect(route('/dashboard'));
    }
}
