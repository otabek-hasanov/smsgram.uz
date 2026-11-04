<?php

namespace App\Models;

use App\Core\Model;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class Payment extends Model
{
    protected static string $table = 'payments';

    public static function pendingForUser(int $userId): array
    {
        return static::db()->select(
            "SELECT * FROM `payments` WHERE user_id = ? AND status = 'pending' ORDER BY id DESC",
            [$userId]
        );
    }

    public static function findByCheckoutId(int $checkoutPaymentId): ?array
    {
        return static::findBy('checkout_payment_id', $checkoutPaymentId);
    }

    public static function markFailed(int $id): void
    {
        static::updateById($id, ['status' => 'failed']);
    }

    /**
     * Berilgan holatdan `paid`ga faqat bir marta o'tishini kafolatlaydi
     * (bir xil to'lov ikki marta kreditlanmasligi uchun).
     */
    public static function claimForCrediting(int $id): bool
    {
        $affected = static::db()->run(
            "UPDATE `payments` SET `status` = 'paid', `paid_at` = NOW() WHERE `id` = ? AND `status` = 'pending'",
            [$id]
        )->affected_rows;

        return $affected > 0;
    }
}
