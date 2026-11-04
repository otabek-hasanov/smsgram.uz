<?php

namespace App\Http\Controllers\User;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;
use App\Models\ApiKey;
use App\Models\User;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class ProfileController
{
    public static function show(): void
    {
        $user = Auth::user();
        $apiKeys = ApiKey::where('user_id', $user['id']);

        view('user.profile', [
            'title'   => __('profile.title'),
            'user'    => $user,
            'apiKeys' => $apiKeys,
        ], 'layouts.dashboard');
    }

    public static function update(): void
    {
        if (!Session::checkCsrf(Request::input('_token'))) {
            Session::flash('error', 'Sessiya muddati tugagan.');
            redirect(route('/profile'));
        }

        $user = Auth::user();
        $name = trim((string) Request::input('name'));
        $phone = trim((string) Request::input('phone'));

        User::updateById($user['id'], [
            'name'  => $name !== '' ? $name : $user['name'],
            'phone' => $phone ?: null,
        ]);

        Session::flash('success', 'Profil yangilandi.');
        redirect(route('/profile'));
    }

    public static function createApiKey(): void
    {
        if (!Session::checkCsrf(Request::input('_token'))) {
            Session::flash('error', 'Sessiya muddati tugagan.');
            redirect(route('/profile'));
        }

        $user = Auth::user();
        $name = trim((string) Request::input('name', 'API kalit'));

        ApiKey::generateFor($user['id'], $name ?: 'API kalit');

        Session::flash('success', 'Yangi API kalit yaratildi.');
        redirect(route('/profile'));
    }
}
