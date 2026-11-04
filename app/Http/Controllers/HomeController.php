<?php

namespace App\Http\Controllers;

use App\Models\Setting;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class HomeController
{
    public static function index(): void
    {
        $price = (int) Setting::get('price_per_sms', 200);

        view('home', [
            'title'       => __('seo.home_title'),
            'description' => __('seo.home_desc', ['price' => $price]),
            'price'       => $price,
        ], 'layouts.app');
    }
}
