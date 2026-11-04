<?php

namespace App\Http\Controllers\User;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;
use App\Models\Setting;
use App\Models\SmsTemplate;
use App\Models\Transaction;
use App\Models\User;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class TemplateController
{
    public static function index(): void
    {
        $user = Auth::user();

        view('user.templates.index', [
            'title'     => __('templates.my_templates'),
            'user'      => $user,
            'templates' => SmsTemplate::forUser($user['id']),
        ], 'layouts.dashboard');
    }

    public static function create(): void
    {
        view('user.templates.create', [
            'title' => __('templates.create_title'),
            'user'  => Auth::user(),
            'price' => (float) Setting::get('template_price', 20000),
        ], 'layouts.dashboard');
    }

    public static function store(): void
    {
        if (!Session::checkCsrf(Request::input('_token'))) {
            Session::flash('error', 'Sessiya muddati tugagan.');
            redirect(route('/templates/create'));
        }

        $user = Auth::user();
        $name = trim((string) Request::input('name'));
        $content = trim((string) Request::input('content'));
        $price = (float) Setting::get('template_price', 20000);

        if ($name === '' || $content === '') {
            Session::flash('error', 'Shablon nomi va matnini kiriting.');
            redirect(route('/templates/create'));
        }

        if ((float) $user['balance'] < $price) {
            Session::flash('error', 'Balansingiz yetarli emas. Shablon narxi: ' . money($price));
            redirect(route('/balance'));
        }

        SmsTemplate::create([
            'user_id' => $user['id'],
            'name'    => $name,
            'content' => $content,
            'price'   => $price,
            'status'  => 'pending',
        ]);

        User::decrementBalance($user['id'], $price);
        $newBalance = (float) $user['balance'] - $price;
        Transaction::record($user['id'], 'charge', $price, $newBalance, "Shablon yaratish: {$name}");

        Session::flash('success', 'Shablon yuborildi va tekshiruvga qo\'yildi. Tasdiqlangach SMS yuborish uchun ishlatishingiz mumkin bo\'ladi.');
        redirect(route('/templates'));
    }
}
