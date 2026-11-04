<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;

defined('SMSGRAM') or die('Direct access is not allowed.');

/**
 * To'lovni checkout.uz'ning o'zidan (bizning API-kalitimiz bilan) qayta
 * tasdiqlab, faqat shundan so'ng balansni kreditlaydi. Bu ham webhook
 * ichida, ham foydalanuvchi /balance sahifasiga qaytganda (webhook
 * kelmay qolgan holatlar uchun zaxira sifatida) chaqiriladi.
 */
final class PaymentReconciler
{
    public static function confirmAndCredit(array $payment): bool
    {
        if ($payment['status'] !== 'pending' || !$payment['checkout_payment_id']) {
            return $payment['status'] === 'paid';
        }

        $checkout = new CheckoutService();
        $result = $checkout->statusPayment((int) $payment['checkout_payment_id']);

        if (!$result['success'] || ($result['data']['status'] ?? null) !== 'paid') {
            return false;
        }

        // Atomik "claim" — bir xil to'lov parallel so'rovlarda (webhook +
        // sahifa yuklanishi) ikki marta kreditlanmasligini kafolatlaydi.
        if (!Payment::claimForCrediting((int) $payment['id'])) {
            return true;
        }

        $amount = (float) $payment['amount'];
        $user = User::find((int) $payment['user_id']);

        User::incrementBalance($user['id'], $amount);
        $newBalance = (float) $user['balance'] + $amount;
        Transaction::record($user['id'], 'topup', $amount, $newBalance, "Checkout.uz orqali to'ldirish #{$payment['id']}");

        return true;
    }
}
