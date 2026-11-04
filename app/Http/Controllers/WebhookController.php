<?php

namespace App\Http\Controllers;

use App\Core\Request;
use App\Models\Payment;
use App\Services\PaymentReconciler;

defined('SMSGRAM') or die('Direct access is not allowed.');

/**
 * Tashqi to'lov tizimlaridan keladigan webhook'lar shu yerda qabul qilinadi.
 *
 * Checkout.uz webhook'i imzo (signature) bilan tasdiqlanmaydi (rasmiy
 * hujjatlarida yo'q), shuning uchun kelgan ma'lumotga ko'r-ko'rona
 * ishonmaymiz — har doim o'z API-kalitimiz bilan checkout.uz'dan
 * to'lov holatini qayta so'raymiz (PaymentReconciler), shundan keyingina
 * balans kreditlanadi. Soxta POST so'rovi orqali balans oshirib
 * bo'lmaydi.
 */
final class WebhookController
{
    public static function checkout(): void
    {
        $payload = Request::json() ?: Request::all();
        $checkoutPaymentId = (int) ($payload['data']['order_id'] ?? 0);

        if ($checkoutPaymentId <= 0) {
            json_response(['success' => false, 'error' => 'order_id missing'], 400);
            return;
        }

        $payment = Payment::findByCheckoutId($checkoutPaymentId);

        if (!$payment) {
            json_response(['success' => false, 'error' => 'payment not found'], 404);
            return;
        }

        PaymentReconciler::confirmAndCredit($payment);

        json_response(['success' => true]);
    }
}
