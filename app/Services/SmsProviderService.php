<?php

namespace App\Services;

use App\Core\Cache;
use App\Core\Config;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

defined('SMSGRAM') or die('Direct access is not allowed.');

/**
 * Tashqi SMS yetkazish platformasi bilan ishlash qatlami (GuzzleHttp orqali).
 * Provayder nomi foydalanuvchiga hech qachon ko'rsatilmaydi — bu sof
 * backend integratsiyasi. Auth token Redis'da keshlanadi (tezkorlik uchun).
 */
final class SmsProviderService
{
    private const TOKEN_CACHE_KEY = 'sms_provider_access_token';
    private const TOKEN_TTL = 3000; // ~50 daqiqa

    private Client $authClient;
    private Client $smsClient;
    private array $cfg;

    public function __construct()
    {
        $this->cfg = Config::get('sms_provider');

        $this->authClient = new Client([
            'base_uri' => $this->cfg['auth_base_uri'],
            'timeout'  => $this->cfg['timeout'] ?? 10,
        ]);

        $this->smsClient = new Client([
            'base_uri' => $this->cfg['sms_base_uri'],
            'timeout'  => $this->cfg['timeout'] ?? 10,
        ]);
    }

    /**
     * @param string[] $recipients Telefon raqamlar ro'yxati (+998...)
     * @return array{success: bool, sms_id: ?string, error: ?string, raw: mixed}
     */
    public function send(array $recipients, string $message, ?string $providerTemplateId = null, string $name = 'SMSGram'): array
    {
        try {
            $token = $this->getAccessToken();

            $payload = [
                'userId'     => $this->cfg['user_id'],
                'name'       => $name,
                'recipients' => $recipients,
            ];

            if ($providerTemplateId) {
                $payload['templateId'] = $providerTemplateId;
            } else {
                $payload['message'] = $message;
            }

            if (!empty($this->cfg['nickname_id'])) {
                $payload['nicknameId'] = $this->cfg['nickname_id'];
            }

            $response = $this->smsClient->post('send', [
                'headers' => ['Authorization' => "Bearer {$token}"],
                'json'    => $payload,
            ]);

            $data = json_decode((string) $response->getBody(), true);

            return [
                'success' => isset($data['smsId']),
                'sms_id'  => $data['smsId'] ?? null,
                'error'   => null,
                'raw'     => $data,
            ];
        } catch (GuzzleException $e) {
            return ['success' => false, 'sms_id' => null, 'error' => $e->getMessage(), 'raw' => null];
        }
    }

    public function getStatus(string $smsId): array
    {
        try {
            $token = $this->getAccessToken();

            $response = $this->smsClient->get("sms/{$smsId}", [
                'headers' => ['Authorization' => "Bearer {$token}"],
            ]);

            return ['success' => true, 'raw' => json_decode((string) $response->getBody(), true)];
        } catch (GuzzleException $e) {
            return ['success' => false, 'error' => $e->getMessage(), 'raw' => null];
        }
    }

    private function getAccessToken(): string
    {
        return Cache::remember(self::TOKEN_CACHE_KEY, self::TOKEN_TTL, function () {
            $response = $this->authClient->post('login', [
                'json' => [
                    'email'    => $this->cfg['email'],
                    'password' => $this->cfg['password'],
                ],
            ]);

            $data = json_decode((string) $response->getBody(), true);

            return $data['accessToken'] ?? '';
        });
    }
}
