<?php

namespace App\Http\Controllers;

use App\Services\SitemapCrawler;

defined('SMSGRAM') or die('Direct access is not allowed.');

/**
 * sitemap.xml statik fayl emas — App\Services\SitemapCrawler orqali
 * saytning o'zini kezib, haqiqatda mavjud sahifalarni dinamik topib
 * shakillantiradi.
 */
final class SitemapController
{
    public static function show(): void
    {
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        $baseUrl = 'https://' . ($_SERVER['HTTP_HOST'] ?? 'smsgram.uz');

        $xml = (new SitemapCrawler($baseUrl))->generate();

        header('Content-Type: application/xml; charset=UTF-8');
        header('X-Robots-Tag: noindex');

        echo $xml;
    }
}
