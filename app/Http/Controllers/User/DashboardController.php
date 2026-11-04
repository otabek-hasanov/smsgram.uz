<?php

namespace App\Http\Controllers\User;

use App\Core\Auth;
use App\Models\SmsMessage;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class DashboardController
{
    public static function index(): void
    {
        $user = Auth::user();
        $stats = SmsMessage::statsForUser($user['id']);
        $recent = SmsMessage::forUser($user['id'], 1, 5)['items'];

        view('user.dashboard', [
            'title'  => __('dashboard.title'),
            'user'   => $user,
            'stats'  => $stats,
            'recent' => $recent,
        ], 'layouts.dashboard');
    }
}
