<?php

namespace App\Core;

defined('SMSGRAM') or die('Direct access is not allowed.');

/**
 * Foydalanuvchi va admin panel sidebar menyulari shu yerdan olinadi —
 * view fayllarida statik massiv yozilmasligi uchun.
 */
final class NavMenu
{
    public static function user(): array
    {
        return [
            ['href' => route('/dashboard'), 'label' => __('nav.dashboard'), 'icon' => 'home'],
            ['href' => route('/sms/send'), 'label' => __('nav.send_sms'), 'icon' => 'send'],
            ['href' => route('/sms/history'), 'label' => __('nav.history'), 'icon' => 'clock'],
            ['href' => route('/templates'), 'label' => __('nav.templates'), 'icon' => 'template'],
            ['href' => route('/balance'), 'label' => __('nav.balance'), 'icon' => 'card'],
            ['href' => route('/profile'), 'label' => __('nav.profile'), 'icon' => 'settings'],
        ];
    }

    /**
     * Admin panel — til prefiksisiz, doim o'zbek tilida (tarjima tizimidan mustaqil).
     */
    public static function admin(): array
    {
        return [
            ['href' => '/admin', 'label' => 'Boshqaruv paneli', 'icon' => 'home'],
            ['href' => '/admin/users', 'label' => 'Foydalanuvchilar', 'icon' => 'users'],
            ['href' => '/admin/templates', 'label' => 'Shablonlar', 'icon' => 'template'],
            ['href' => '/admin/transactions', 'label' => 'Tranzaksiyalar', 'icon' => 'banknotes'],
            ['href' => '/admin/settings', 'label' => 'Sozlamalar', 'icon' => 'settings'],
        ];
    }

    public static function locales(): array
    {
        return [
            'uz' => "O'zbek",
            'ru' => 'Русский',
            'en' => 'English',
        ];
    }
}
