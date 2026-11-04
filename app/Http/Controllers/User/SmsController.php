<?php

namespace App\Http\Controllers\User;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;
use App\Models\Setting;
use App\Models\SmsMessage;
use App\Models\SmsTemplate;
use App\Models\Transaction;
use App\Models\User;
use App\Services\PhoneValidator;
use App\Services\SmsProviderService;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class SmsController
{
    public static function create(): void
    {
        $user = Auth::user();
        $templates = SmsTemplate::approvedForUser($user['id']);

        foreach ($templates as &$tpl) {
            $tpl['variables'] = SmsTemplate::extractVariables($tpl['content']);
        }
        unset($tpl);

        view('user.sms.send', [
            'title'     => __('sms.send_title'),
            'user'      => $user,
            'templates' => $templates,
        ], 'layouts.dashboard');
    }

    public static function store(): void
    {
        if (!Session::checkCsrf(Request::input('_token'))) {
            Session::flash('error', 'Sessiya muddati tugagan.');
            redirect(route('/sms/send'));
        }

        $user = Auth::user();
        $templateId = (int) Request::input('template_id');
        $parsed = PhoneValidator::parseAndValidateList((string) Request::input('recipients', ''));
        $recipients = $parsed['valid'];
        $variables = (array) Request::input('variables', []);

        if (!empty($parsed['invalid'])) {
            Session::flash('error', __('sms.invalid_recipients', ['numbers' => implode(', ', $parsed['invalid'])]));
            redirect(route('/sms/send'));
        }

        if ($templateId <= 0 || empty($recipients)) {
            Session::flash('error', 'Shablonni tanlang va kamida bitta raqam kiriting.');
            redirect(route('/sms/send'));
        }

        $template = SmsTemplate::find($templateId);

        if (!$template || (int) $template['user_id'] !== (int) $user['id'] || $template['status'] !== 'approved') {
            Session::flash('error', 'Tanlangan shablon mavjud emas yoki hali tasdiqlanmagan.');
            redirect(route('/sms/send'));
        }

        $requiredVars = SmsTemplate::extractVariables($template['content']);
        $values = [];

        foreach ($requiredVars as $varName) {
            $value = trim((string) ($variables[$varName] ?? ''));

            if ($value === '') {
                Session::flash('error', __('templates.fill_variables'));
                redirect(route('/sms/send'));
            }

            $values[$varName] = $value;
        }

        $message = SmsTemplate::render($template['content'], $values);

        $pricePerSms = (float) Setting::get('price_per_sms', 200);
        $totalPrice = $pricePerSms * count($recipients);

        if ((float) $user['balance'] < $totalPrice) {
            Session::flash('error', 'Balansingiz yetarli emas. Kerakli summa: ' . money($totalPrice));
            redirect(route('/balance'));
        }

        $provider = new SmsProviderService();
        $result = $provider->send($recipients, $message, $template['provider_template_id']);

        $smsId = SmsMessage::create([
            'user_id'           => $user['id'],
            'template_id'       => $template['id'],
            'recipients'        => implode(',', $recipients),
            'recipients_count'  => count($recipients),
            'textup_sms_id'     => $result['sms_id'],
            'message'           => $message,
            'status'            => $result['success'] ? 'sent' : 'failed',
            'cost'              => $result['success'] ? $totalPrice : 0,
            'provider_response' => json_encode($result['raw'] ?? $result['error']),
        ]);

        if ($result['success']) {
            User::decrementBalance($user['id'], $totalPrice);
            $newBalance = (float) $user['balance'] - $totalPrice;
            Transaction::record($user['id'], 'charge', $totalPrice, $newBalance, "SMS #{$smsId} uchun to'lov ({$template['name']})");
            Session::flash('success', 'SMS ' . count($recipients) . ' ta raqamga muvaffaqiyatli yuborildi.');
        } else {
            Session::flash('error', 'SMS yuborishda xatolik: ' . ($result['error'] ?? 'Noma\'lum xato'));
        }

        redirect(route('/sms/history'));
    }

    public static function history(): void
    {
        $user = Auth::user();
        $page = max(1, (int) Request::input('page', 1));

        view('user.sms.history', [
            'title'      => __('sms.history_title'),
            'user'       => $user,
            'pagination' => SmsMessage::forUser($user['id'], $page, 20),
        ], 'layouts.dashboard');
    }
}
