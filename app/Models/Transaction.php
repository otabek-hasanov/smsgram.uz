<?php

namespace App\Models;

use App\Core\Model;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class Transaction extends Model
{
    protected static string $table = 'transactions';

    public static function record(int $userId, string $type, float $amount, float $balanceAfter, string $description = ''): int
    {
        return static::create([
            'user_id'       => $userId,
            'type'          => $type,
            'amount'        => $amount,
            'balance_after' => $balanceAfter,
            'description'   => $description,
        ]);
    }

    public static function forUser(int $userId, int $page = 1, int $perPage = 20): array
    {
        $db = static::db();
        $offset = ($page - 1) * $perPage;

        $items = $db->select(
            'SELECT * FROM `transactions` WHERE user_id = ? ORDER BY id DESC LIMIT ? OFFSET ?',
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
}
