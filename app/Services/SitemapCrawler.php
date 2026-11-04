<?php

namespace App\Services;

use DOMDocument;
use XMLWriter;

defined('SMSGRAM') or die('Direct access is not allowed.');

/**
 * Sitemap.xml'ni statik ro'yxatdan emas, saytning o'zini (o'ziga HTTP
 * so'rov yuborib) kezib chiqish orqali dinamik shakillantiradi. Sahifalar
 * bosh sahifadagi va undan kelib chiqadigan havolalar (<a href>) orqali
 * avtomatik topiladi — shu sababli yangi sahifa qo'shilsa, kod
 * o'zgartirilmasdan sitemap'ga o'zi qo'shiladi. Faqat haqiqatan 200 OK
 * qaytargan sahifalar kiritiladi (masalan, autentifikatsiya talab
 * qiladigan sahifalar anonim kezishda 302'ga yo'naltirilgani uchun
 * o'z-o'zidan chetlanadi).
 */
final class SitemapCrawler
{
    private const HOME_LOCALES = ['uz', 'ru', 'en'];

    private const EXCLUDED_PATH_SEGMENTS = [
        '/admin', '/dashboard', '/templates', '/sms', '/balance',
        '/profile', '/logout', '/api/', '/webhooks/',
    ];

    private const EXCLUDED_SUFFIXES = [
        'favicon', 'icon-', 'apple-touch', 'og-image',
        '.svg', '.png', '.jpg', '.jpeg', '.gif', '.ico',
        '.css', '.js', '.webmanifest', '.xml', '.txt', '.json',
    ];

    private string $baseUrl;

    /** @var array<int, string> */
    private array $visited = [];

    /** @var array<int, string> */
    private array $queue = [];

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    public function generate(int $maxPages = 200): string
    {
        libxml_use_internal_errors(true);

        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElement('urlset');
        $xml->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        foreach (self::HOME_LOCALES as $locale) {
            $this->queue[] = $this->baseUrl . '/' . $locale;
        }

        while ($this->queue !== [] && count($this->visited) < $maxPages) {
            $url = rtrim((string) array_shift($this->queue), '/');

            if (in_array($url, $this->visited, true)) {
                continue;
            }
            $this->visited[] = $url;

            $content = $this->fetch($url);
            if ($content === null) {
                continue;
            }

            $xml->startElement('url');
            $xml->writeElement('loc', $url);
            $xml->writeElement('lastmod', date('c'));
            $xml->writeElement('changefreq', $this->changefreqFor($url));
            $xml->writeElement('priority', $this->priorityFor($url));
            $xml->endElement();

            $this->extractLinks($content);
        }

        $xml->endElement();
        $xml->endDocument();

        libxml_clear_errors();

        return $xml->outputMemory();
    }

    private function fetch(string $url): ?string
    {
        $context = stream_context_create([
            'http' => [
                'timeout' => 5,
                'ignore_errors' => true,
                'header' => "User-Agent: Mozilla/5.0 (compatible; SMSGramSitemapBot)\r\n",
            ],
        ]);

        $content = @file_get_contents($url, false, $context);

        if ($content === false) {
            return null;
        }

        $status = $http_response_header[0] ?? '';
        if (!str_contains($status, '200')) {
            return null;
        }

        return $content;
    }

    private function extractLinks(string $html): void
    {
        if ($html === '') {
            return;
        }

        $dom = new DOMDocument();
        @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_NOWARNING | LIBXML_NOERROR);

        foreach ($dom->getElementsByTagName('a') as $link) {
            $href = $link->getAttribute('href');

            if ($href === '' || $href[0] === '#'
                || str_starts_with($href, 'mailto:')
                || str_starts_with($href, 'tel:')
                || str_starts_with($href, 'javascript:')
            ) {
                continue;
            }

            if (!str_starts_with($href, 'http')) {
                $href = $this->baseUrl . '/' . ltrim($href, '/');
            }

            if (!str_starts_with($href, $this->baseUrl)) {
                continue;
            }

            $href = rtrim(explode('#', $href)[0], '/');

            if ($this->isExcluded($href)) {
                continue;
            }

            if (!in_array($href, $this->visited, true) && !in_array($href, $this->queue, true)) {
                $this->queue[] = $href;
            }
        }
    }

    private function isExcluded(string $url): bool
    {
        $path = (string) parse_url($url, PHP_URL_PATH);

        foreach (self::EXCLUDED_PATH_SEGMENTS as $segment) {
            if (str_contains($path, $segment)) {
                return true;
            }
        }

        foreach (self::EXCLUDED_SUFFIXES as $needle) {
            if (str_contains($path, $needle)) {
                return true;
            }
        }

        return false;
    }

    private function changefreqFor(string $url): string
    {
        $path = (string) parse_url($url, PHP_URL_PATH);

        return preg_match('#^/(uz|ru|en)$#', $path) === 1 ? 'weekly' : 'monthly';
    }

    private function priorityFor(string $url): string
    {
        $path = (string) parse_url($url, PHP_URL_PATH);

        if (preg_match('#^/(uz|ru|en)$#', $path) === 1) {
            return str_starts_with($path, '/uz') ? '1.0' : '0.9';
        }

        return str_contains($path, '/docs') ? '0.8' : '0.6';
    }
}
