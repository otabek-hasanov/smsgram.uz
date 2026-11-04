<?php

namespace App\Config;

defined('SMSGRAM') or die('Direct access is not allowed.');

/**
 * Butun ilova konfiguratsiyasi — to'g'ridan-to'g'ri PHP klass ichida
 * (env fayl yo'q, alohida require qilinadigan fayl ham yo'q).
 */
final class AppConfig
{
    public static function all(): array
    {
        return [

            'app' => [
                'name'     => 'SMSGram',
                'env'      => 'production',
                'debug'    => false,
                'url'      => 'https://smsgram.uz',
                'timezone' => 'Asia/Tashkent',
                'locales'  => ['uz', 'ru', 'en'],
                'default_locale' => 'uz',
            ],

            'database' => [
                'host'     => 'localhost',
                'port'     => 3306,
                'database' => 'smsgram',
                'username' => 'smsgram',
                'password' => 'CHANGE_ME',
                'charset'  => 'utf8mb4',
            ],

            'redis' => [
                'scheme'   => 'tcp',
                'host'     => '127.0.0.1',
                'port'     => 6379,
                'database' => 0,
                'prefix'   => 'smsgram:',
            ],

            'session' => [
                'name'     => 'smsgram_session',
                'lifetime' => 7200,
                'secure'   => true,
                'httponly' => true,
                'samesite' => 'Lax',
            ],

            // Tashqi SMS yetkazish platformasi (foydalanuvchiga ko'rsatilmaydi,
            // faqat backend darajasida ishlatiladi)
            'sms_provider' => [
                'auth_base_uri' => 'https://api-auth.textup.uz/v1/',
                'sms_base_uri'  => 'https://sms-api.textup.uz/v1/',
                'email'         => 'CHANGE_ME',
                'password'      => 'CHANGE_ME',
                'user_id'       => 'CHANGE_ME',
                'nickname_id'   => null,
                'timeout'       => 10,
            ],

            'sms' => [
                'price_per_sms'  => 200,
                'template_price' => 20000,
            ],

            // Checkout.uz to'lov agregatori (balansni avtomatik to'ldirish uchun)
            'checkout' => [
                'base_uri'    => 'https://checkout.uz/api/v1/',
                'api_key'     => 'CHANGE_ME',
                'webhook_url' => 'https://your-domain.tld/webhooks/checkout',
                'min_amount'  => 1000,
                'max_amount'  => 10000000,
                'timeout'     => 15,
            ],

        ];
    }
}
