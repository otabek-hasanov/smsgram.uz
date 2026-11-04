<?php

namespace App\Services;

use App\Core\Config;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

defined('SMSGRAM') or die('Direct access is not allowed.');

/**
 * Checkout.uz to'lov agregatori bilan integratsiya (Click, Payme, Uzcard/Humo).
 * https://checkout.uz/api-docs
 *
 * Muhim: checkout.uz webhook'lari imzo (signature) bilan tasdiqlanmaydi,
 * shuning uchun webhook kelganda balansni darhol kreditlash xavfli —
 * har doim statusPayment() orqali o'z API-kalitimiz bilan haqiqiyligini
 * qayta tekshirish shart (App\Http\Controllers\WebhookController'da shunday
 * qilingan).
 */
final class CheckoutService
{
    private Client $http;
    private array $cfg;

    public function __construct()
    {
        $this->cfg = Config::get('checkout');

        $this->http = new Client([
            'base_uri' => $this->cfg['base_uri'],
            'timeout'  => $this->cfg['timeout'] ?? 15,
        ]);
    }

    /**
     * @return array{success: bool, payment: ?array, error: ?string}
     */
    public function createPayment(float $amount, string $description, string $returnUrl): array
    {
        try {
            $response = $this->http->post('create_payment', [
                'headers' => ['Authorization' => "Bearer {$this->cfg['api_key']}"],
                'json'    => [
                    'amount'      => (int) $amount,
                    'description' => $description,
                    'webhook_url' => $this->cfg['webhook_url'],
                    'return_url'  => $returnUrl,
                ],
            ]);

            $data = json_decode((string) $response->getBody(), true);

            return [
                'success' => ($data['status'] ?? '') === 'success',
                'payment' => $data['payment'] ?? null,
                'error'   => null,
            ];
        } catch (GuzzleException $e) {
            return ['success' => false, 'payment' => null, 'error' => $e->getMessage()];
        }
    }

    /**
     * @return array{success: bool, data: ?array, error: ?string}
     */
    public function statusPayment(int $checkoutPaymentId): array
    {
        try {
            $response = $this->http->post('status_payment', [
                'headers' => ['Authorization' => "Bearer {$this->cfg['api_key']}"],
                'json'    => ['id' => $checkoutPaymentId],
            ]);

            $data = json_decode((string) $response->getBody(), true);

            return [
                'success' => ($data['status'] ?? '') === 'success',
                'data'    => $data['data'] ?? null,
                'error'   => null,
            ];
        } catch (GuzzleException $e) {
            return ['success' => false, 'data' => null, 'error' => $e->getMessage()];
        }
    }

    public function minAmount(): int
    {
        return (int) $this->cfg['min_amount'];
    }

    public function maxAmount(): int
    {
        return (int) $this->cfg['max_amount'];
    }
}
