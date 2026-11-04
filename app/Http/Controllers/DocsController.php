<?php

namespace App\Http\Controllers;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class DocsController
{
    public static function show(): void
    {
        view('docs', [
            'title'       => __('docs.title') . ' — ' . __('common.app_name'),
            'description' => __('docs.subtitle'),
        ], 'layouts.app');
    }
}
