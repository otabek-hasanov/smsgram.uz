<?php

namespace App\Http\Controllers\Admin;

use App\Core\Request;
use App\Core\Session;
use App\Models\Transaction;
use App\Models\User;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class UserController
{
    public static function index(): void
    {
        $page = max(1, (int) Request::input('page', 1));
        $pagination = User::paginate($page, 20);

        view('admin.users.index', [
            'title'      => 'Foydalanuvchilar',
            'pagination' => $pagination,
        ], 'layouts.admin');
    }

    public static function show(int $id): void
    {
        $user = User::find($id);

        if (!$user) {
            abort_with(404, 'Foydalanuvchi topilmadi');
        }

        $transactions = Transaction::forUser($id, 1, 15);

        view('admin.users.show', [
            'title'        => 'Foydalanuvchi: ' . $user['name'],
            'user'         => $user,
            'transactions' => $transactions,
        ], 'layouts.admin');
    }

    public static function adjustBalance(int $id): void
    {
        if (!Session::checkCsrf(Request::input('_token'))) {
            Session::flash('error', 'Sessiya muddati tugagan.');
            redirect('/admin/users/' . $id);
        }

        $user = User::find($id);

        if (!$user) {
            abort_with(404, 'Foydalanuvchi topilmadi');
        }

        $amount = (float) Request::input('amount', 0);
        $type = Request::input('type') === 'deduct' ? 'deduct' : 'topup';

        if ($amount <= 0) {
            Session::flash('error', 'To\'g\'ri summa kiriting.');
            redirect('/admin/users/' . $id);
        }

        if ($type === 'deduct') {
            User::decrementBalance($id, $amount);
            $newBalance = (float) $user['balance'] - $amount;
            Transaction::record($id, 'charge', $amount, $newBalance, 'Admin tomonidan yechildi');
        } else {
            User::incrementBalance($id, $amount);
            $newBalance = (float) $user['balance'] + $amount;
            Transaction::record($id, 'topup', $amount, $newBalance, 'Admin tomonidan to\'ldirildi');
        }

        Session::flash('success', 'Balans yangilandi.');
        redirect('/admin/users/' . $id);
    }

    public static function toggleStatus(int $id): void
    {
        if (!Session::checkCsrf(Request::input('_token'))) {
            Session::flash('error', 'Sessiya muddati tugagan.');
            redirect('/admin/users/' . $id);
        }

        $user = User::find($id);

        if (!$user) {
            abort_with(404, 'Foydalanuvchi topilmadi');
        }

        $newStatus = $user['status'] === 'active' ? 'banned' : 'active';
        User::updateById($id, ['status' => $newStatus]);

        Session::flash('success', 'Holat yangilandi: ' . $newStatus);
        redirect('/admin/users/' . $id);
    }
}
