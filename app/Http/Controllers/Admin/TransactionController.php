<?php

namespace App\Http\Controllers\Admin;

use App\Core\Request;
use App\Models\Transaction;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class TransactionController
{
    public static function index(): void
    {
        $page = max(1, (int) Request::input('page', 1));
        $pagination = Transaction::paginate($page, 25);

        view('admin.transactions.index', [
            'title'      => 'Tranzaksiyalar',
            'pagination' => $pagination,
        ], 'layouts.admin');
    }
}
