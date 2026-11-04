<?php

namespace App\Models;

use App\Core\Model;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class SmsTemplate extends Model
{
    protected static string $table = 'sms_templates';

    public static function forUser(int $userId): array
    {
        return static::where('user_id', $userId, 'id DESC');
    }

    public static function approvedForUser(int $userId): array
    {
        return static::db()->select(
            "SELECT * FROM `sms_templates` WHERE user_id = ? AND status = 'approved' ORDER BY name ASC",
            [$userId]
        );
    }

    public static function pending(int $page = 1, int $perPage = 20): array
    {
        $db = static::db();
        $offset = ($page - 1) * $perPage;

        $items = $db->select(
            "SELECT t.*, u.name as user_name, u.email as user_email
             FROM sms_templates t JOIN users u ON u.id = t.user_id
             WHERE t.status = 'pending' ORDER BY t.id ASC LIMIT ? OFFSET ?",
            [$perPage, $offset]
        );

        $total = static::count("status = 'pending'");

        return [
            'items'     => $items,
            'total'     => $total,
            'page'      => $page,
            'per_page'  => $perPage,
            'last_page' => (int) max(1, ceil($total / $perPage)),
        ];
    }

    public static function all(string $orderBy = 'id DESC'): array
    {
        return static::db()->select(
            "SELECT t.*, u.name as user_name, u.email as user_email
             FROM sms_templates t JOIN users u ON u.id = t.user_id
             ORDER BY t.{$orderBy}"
        );
    }

    public static function approve(int $id, ?string $providerTemplateId = null): void
    {
        static::updateById($id, [
            'status'               => 'approved',
            'provider_template_id' => $providerTemplateId,
        ]);
    }

    public static function reject(int $id, string $note = ''): void
    {
        static::updateById($id, [
            'status'     => 'rejected',
            'admin_note' => $note,
        ]);
    }

    /**
     * Shablon matnidagi {o'zgaruvchan} qismlarni topib beradi.
     * Masalan: "Kodingiz: {code}" -> ['code']
     *
     * @return string[]
     */
    public static function extractVariables(string $content): array
    {
        preg_match_all('/\{([a-zA-Z0-9_]+)\}/', $content, $matches);

        return array_values(array_unique($matches[1]));
    }

    /**
     * Shablon matnidagi {o'zgaruvchi} larni berilgan qiymatlar bilan almashtiradi.
     *
     * @param array<string, string> $variables
     */
    public static function render(string $content, array $variables): string
    {
        foreach ($variables as $name => $value) {
            $content = str_replace('{' . $name . '}', $value, $content);
        }

        return $content;
    }
}
