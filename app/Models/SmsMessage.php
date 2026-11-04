<?php

namespace App\Models;

use App\Core\Model;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class SmsMessage extends Model
{
    protected static string $table = 'sms_messages';

    public static function forUser(int $userId, int $page = 1, int $perPage = 20): array
    {
        $db = static::db();
        $offset = ($page - 1) * $perPage;

        $items = $db->select(
            'SELECT sm.*, t.name as template_name FROM `sms_messages` sm
             LEFT JOIN `sms_templates` t ON t.id = sm.template_id
             WHERE sm.user_id = ? ORDER BY sm.id DESC LIMIT ? OFFSET ?',
            [$userId, $perPage, $offset]
        );

        $total = static::count('user_id = ?', [$userId]);

        return [
            'items'     => $items,
            'total'     => $total,
            'page'      => $page,
            'per_page'  => $perPage,
            'last_page' => (int) max(1, ceil($total / $perPage)),
        ];
    }

    public static function statsForUser(int $userId): array
    {
        $row = static::db()->selectOne(
            "SELECT
                COUNT(*) as total,
                SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered,
                SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed,
                SUM(cost) as total_spent
             FROM sms_messages WHERE user_id = ?",
            [$userId]
        );

        return $row ?: ['total' => 0, 'delivered' => 0, 'failed' => 0, 'total_spent' => 0];
    }
}
