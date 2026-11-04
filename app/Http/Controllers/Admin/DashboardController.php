<?php

namespace App\Http\Controllers\Admin;

use App\Core\Database;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class DashboardController
{
    public static function index(): void
    {
        $db = Database::getInstance();

        $stats = [
            'users_total'   => $db->selectOne('SELECT COUNT(*) as c FROM users')['c'] ?? 0,
            'sms_total'     => $db->selectOne('SELECT COUNT(*) as c FROM sms_messages')['c'] ?? 0,
            'sms_delivered' => $db->selectOne("SELECT COUNT(*) as c FROM sms_messages WHERE status IN ('sent','delivered')")['c'] ?? 0,
            'revenue'       => $db->selectOne("SELECT COALESCE(SUM(amount),0) as s FROM transactions WHERE type = 'charge'")['s'] ?? 0,
        ];

        $recentUsers = $db->select('SELECT id, name, email, balance, created_at FROM users ORDER BY id DESC LIMIT 5');
        $recentSms = $db->select(
            'SELECT sm.id, sm.recipients_count, sm.status, sm.cost, sm.created_at, u.name as user_name
             FROM sms_messages sm JOIN users u ON u.id = sm.user_id
             ORDER BY sm.id DESC LIMIT 8'
        );

        view('admin.dashboard', [
            'title'       => 'Admin panel',
            'stats'       => $stats,
            'recentUsers' => $recentUsers,
            'recentSms'   => $recentSms,
        ], 'layouts.admin');
    }
}
