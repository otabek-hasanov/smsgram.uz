<?php

namespace App\Http\Controllers\Api;

use App\Core\Request;
use App\Models\ApiKey;
use App\Models\Setting;
use App\Models\SmsMessage;
use App\Models\SmsTemplate;
use App\Models\Transaction;
use App\Models\User;
use App\Services\PhoneValidator;
use App\Services\SmsProviderService;

defined('SMSGRAM') or die('Direct access is not allowed.');

/**
 * Tashqi mijozlar uchun REST API (Bearer API-key orqali autentifikatsiya).
 * Faqat tasdiqlangan shablonlar orqali SMS yuborish mumkin.
 */
final class SmsController
{
    public static function send(): void
    {
        $token = Request::bearerToken();
        $apiKey = $token ? ApiKey::findByKey($token) : null;

        if (!$apiKey) {
            json_response(['success' => false, 'error' => 'API kalit noto\'g\'ri'], 401);
            return;
        }

        $user = User::find($apiKey['user_id']);

        if (!$user || $user['status'] !== 'active') {
            json_response(['success' => false, 'error' => 'Foydalanuvchi bloklangan'], 403);
            return;
        }

        $payload = Request::isJson() ? Request::json() : Request::all();
        $templateId = (int) ($payload['template_id'] ?? 0);
        $phoneCheck = PhoneValidator::validateArray((array) ($payload['recipients'] ?? []));
        $recipients = $phoneCheck['valid'];

        if ($templateId <= 0 || empty($recipients)) {
            json_response(['success' => false, 'error' => 'template_id va recipients maydonlari shart'], 422);
            return;
        }

        if (!empty($phoneCheck['invalid'])) {
            json_response([
                'success' => false,
                'error'   => "Faqat O'zbekiston mobil raqamlari qabul qilinadi",
                'invalid_recipients' => $phoneCheck['invalid'],
            ], 422);
            return;
        }

        $template = SmsTemplate::find($templateId);

        if (!$template || (int) $template['user_id'] !== (int) $user['id'] || $template['status'] !== 'approved') {
            json_response(['success' => false, 'error' => 'Shablon topilmadi yoki tasdiqlanmagan'], 422);
            return;
        }

        $requiredVars = SmsTemplate::extractVariables($template['content']);
        $providedVars = (array) ($payload['variables'] ?? []);
        $values = [];

        foreach ($requiredVars as $varName) {
            $value = trim((string) ($providedVars[$varName] ?? ''));

            if ($value === '') {
                json_response(['success' => false, 'error' => "variables.{$varName} maydoni shart"], 422);
                return;
            }

            $values[$varName] = $value;
        }

        $message = SmsTemplate::render($template['content'], $values);

        $pricePerSms = (float) Setting::get('price_per_sms', 200);
        $totalPrice = $pricePerSms * count($recipients);

        if ((float) $user['balance'] < $totalPrice) {
            json_response(['success' => false, 'error' => 'Balans yetarli emas'], 402);
            return;
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
            Transaction::record($user['id'], 'charge', $totalPrice, $newBalance, "API orqali SMS #{$smsId}");
        }

        ApiKey::touch($apiKey['id']);

        json_response([
            'success'    => $result['success'],
            'sms_id'     => $smsId,
            'provider_sms_id' => $result['sms_id'],
            'recipients' => count($recipients),
            'error'      => $result['error'],
        ], $result['success'] ? 200 : 422);
    }

    public static function balance(): void
    {
        $token = Request::bearerToken();
        $apiKey = $token ? ApiKey::findByKey($token) : null;

        if (!$apiKey) {
            json_response(['success' => false, 'error' => 'API kalit noto\'g\'ri'], 401);
            return;
        }

        $user = User::find($apiKey['user_id']);

        json_response(['success' => true, 'balance' => (float) $user['balance']]);
    }
}
