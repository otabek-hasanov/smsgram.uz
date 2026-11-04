<?php

namespace App\Http\Controllers\Admin;

use App\Core\Request;
use App\Core\Session;
use App\Models\Setting;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class SettingController
{
    public static function index(): void
    {
        view('admin.settings.index', [
            'title'         => 'Sozlamalar',
            'templatePrice' => Setting::get('template_price', 20000),
            'pricePerSms'   => Setting::get('price_per_sms', 200),
        ], 'layouts.admin');
    }

    public static function update(): void
    {
        if (!Session::checkCsrf(Request::input('_token'))) {
            Session::flash('error', 'Sessiya muddati tugagan.');
            redirect('/admin/settings');
        }

        $templatePrice = (float) Request::input('template_price', 20000);
        $pricePerSms = (float) Request::input('price_per_sms', 200);

        Setting::set('template_price', (string) $templatePrice);
        Setting::set('price_per_sms', (string) $pricePerSms);

        Session::flash('success', 'Sozlamalar saqlandi.');
        redirect('/admin/settings');
    }
}
