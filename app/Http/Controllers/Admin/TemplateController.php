<?php

namespace App\Http\Controllers\Admin;

use App\Core\Request;
use App\Core\Session;
use App\Models\SmsTemplate;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class TemplateController
{
    public static function index(): void
    {
        view('admin.templates.index', [
            'title'     => 'Shablonlar',
            'templates' => SmsTemplate::all(),
        ], 'layouts.admin');
    }

    public static function approve(int $id): void
    {
        if (!Session::checkCsrf(Request::input('_token'))) {
            Session::flash('error', 'Sessiya muddati tugagan.');
            redirect('/admin/templates');
        }

        $providerTemplateId = trim((string) Request::input('provider_template_id')) ?: null;

        SmsTemplate::approve($id, $providerTemplateId);

        Session::flash('success', 'Shablon tasdiqlandi.');
        redirect('/admin/templates');
    }

    public static function reject(int $id): void
    {
        if (!Session::checkCsrf(Request::input('_token'))) {
            Session::flash('error', 'Sessiya muddati tugagan.');
            redirect('/admin/templates');
        }

        $note = trim((string) Request::input('admin_note'));

        SmsTemplate::reject($id, $note);

        Session::flash('success', 'Shablon rad etildi.');
        redirect('/admin/templates');
    }
}
