<?php

namespace App\Http\Controllers\User;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;
use App\Models\Payment;
use App\Models\Transaction;
use App\Services\CheckoutService;
use App\Services\PaymentReconciler;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class BalanceController
{
    public static function index(): void
    {
        $user = Auth::user();

        // Webhook kelmay qolgan holatlar uchun zaxira — sahifa ochilganda
        // kutilayotgan to'lovlarni checkout.uz'dan qayta tekshiramiz.
        foreach (Payment::pendingForUser($user['id']) as $pending) {
            PaymentReconciler::confirmAndCredit($pending);
        }

        $user = Auth::user();
        $page = max(1, (int) Request::input('page', 1));
        $checkout = new CheckoutService();

        view('user.balance', [
            'title'           => __('balance.title'),
            'user'            => $user,
            'pagination'      => Transaction::forUser($user['id'], $page, 20),
            'pendingPayments' => Payment::pendingForUser($user['id']),
            'minAmount'       => $checkout->minAmount(),
            'maxAmount'       => $checkout->maxAmount(),
        ], 'layouts.dashboard');
    }

    public static function topup(): void
    {
        if (!Session::checkCsrf(Request::input('_token'))) {
            Session::flash('error', 'Sessiya muddati tugagan.');
            redirect(route('/balance'));
        }

        $user = Auth::user();
        $amount = (float) Request::input('amount', 0);
        $checkout = new CheckoutService();

        if ($amount < $checkout->minAmount() || $amount > $checkout->maxAmount()) {
            Session::flash('error', __('balance.amount_range_error', [
                'min' => money($checkout->minAmount()),
                'max' => money($checkout->maxAmount()),
            ]));
            redirect(route('/balance'));
        }

        $paymentId = Payment::create([
            'user_id' => $user['id'],
            'amount'  => $amount,
            'status'  => 'pending',
        ]);

        $result = $checkout->createPayment(
            $amount,
            __('balance.topup_description', ['app' => __('common.app_name')]),
            'https://smsgram.uz' . route('/balance')
        );

        if (!$result['success'] || !$result['payment']) {
            Payment::markFailed($paymentId);
            Session::flash('error', __('balance.topup_provider_error'));
            redirect(route('/balance'));
        }

        $paymentData = $result['payment'];
        $paymentUrl = $paymentData['_url'] ?? null;

        if (!$paymentUrl) {
            Payment::markFailed($paymentId);
            Session::flash('error', __('balance.topup_provider_error'));
            redirect(route('/balance'));
        }

        Payment::updateById($paymentId, [
            'checkout_payment_id' => $paymentData['_id'] ?? null,
            'checkout_uuid'       => $paymentData['_uuid'] ?? null,
            'payment_url'         => $paymentUrl,
        ]);

        redirect($paymentUrl);
    }
}
