<?php

namespace App\Core;

use Bramus\Router\Router;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class Application
{
    private static ?Router $router = null;

    public static function boot(): void
    {
        $cfg = Config::get('app');

        // Server texnologiyasini (PHP versiyasi) tashqariga oshkor qilmaslik
        header_remove('X-Powered-By');

        date_default_timezone_set($cfg['timezone']);

        error_reporting($cfg['debug'] ? E_ALL : E_ALL & ~E_DEPRECATED & ~E_NOTICE);
        ini_set('display_errors', $cfg['debug'] ? '1' : '0');

        set_exception_handler([self::class, 'handleException']);

        Session::start();
        Translator::init();
    }

    public static function router(): Router
    {
        if (self::$router === null) {
            self::$router = new Router();
        }

        return self::$router;
    }

    public static function handleException(\Throwable $e): void
    {
        error_log(sprintf(
            '[SMSGram] %s in %s:%d',
            $e->getMessage(),
            $e->getFile(),
            $e->getLine()
        ));

        http_response_code(500);

        if (Config::get('app.debug')) {
            echo '<pre>' . htmlspecialchars((string) $e, ENT_QUOTES, 'UTF-8') . '</pre>';

            return;
        }

        $errorView = Paths::views() . '/errors/500.php';

        if (is_file($errorView)) {
            include $errorView;
        } else {
            echo '500 — Serverda xatolik yuz berdi';
        }
    }
}
