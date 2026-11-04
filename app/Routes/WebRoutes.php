<?php

namespace App\Routes;

use App\Core\Middleware\AdminGuestMiddleware;
use App\Core\Middleware\AdminMiddleware;
use App\Core\Middleware\AuthMiddleware;
use App\Core\Middleware\GuestMiddleware;
use App\Core\Translator;
use App\Core\View;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TemplateController as AdminTemplateController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DocsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\User\BalanceController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\SmsController;
use App\Http\Controllers\User\TemplateController;
use App\Http\Controllers\WebhookController;
use Bramus\Router\Router;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class WebRoutes
{
    public static function register(Router $router): void
    {
        // ---------- Til prefiksini URL'dan aniqlash (faqat /uz /ru /en) ----------
        // Non-capturing group emas — bu yerda o'zimiz REQUEST_URI'ni o'qiymiz,
        // shuning uchun bramus'ning parametr sifatida uzatishi muhim emas.
        $router->before('GET|POST', '/(uz|ru|en)(/.*)?', function () {
            $uri = $_SERVER['REQUEST_URI'] ?? '/';
            if (preg_match('#^/(uz|ru|en)(?:/|$)#', $uri, $m)) {
                Translator::setLocale($m[1]);
            }
        });

        // Bosh sahifa (til prefiksisiz) — oxirgi tanlangan yoki standart tilga yo'naltiradi
        $router->get('/', function () {
            redirect(route());
        });

        // sitemap.xml — dinamik (statik fayl emas), Content-Type to'g'ri
        // "application/xml" bo'lishi uchun PHP orqali generatsiya qilinadi
        $router->get('/sitemap.xml', [SitemapController::class, 'show']);

        // ---------- Til prefiksli ommaviy va foydalanuvchi sahifalari ----------
        $router->mount('/(?:uz|ru|en)', function () use ($router) {
            $router->get('/', [HomeController::class, 'index']);
            $router->get('/docs', [DocsController::class, 'show']);

            // Auth (faqat mehmonlar uchun)
            $router->before('GET|POST', '/login', fn () => GuestMiddleware::handle());
            $router->before('GET|POST', '/register', fn () => GuestMiddleware::handle());

            $router->get('/login', [LoginController::class, 'show']);
            $router->post('/login', [LoginController::class, 'login']);
            $router->get('/register', [RegisterController::class, 'show']);
            $router->post('/register', [RegisterController::class, 'register']);
            $router->get('/logout', [LoginController::class, 'logout']);

            // Foydalanuvchi paneli (auth talab qilinadi)
            $router->before('GET|POST', '/dashboard(/.*)?', fn () => AuthMiddleware::handle());
            $router->before('GET|POST', '/templates(/.*)?', fn () => AuthMiddleware::handle());
            $router->before('GET|POST', '/sms(/.*)?', fn () => AuthMiddleware::handle());
            $router->before('GET|POST', '/balance(/.*)?', fn () => AuthMiddleware::handle());
            $router->before('GET|POST', '/profile(/.*)?', fn () => AuthMiddleware::handle());

            $router->get('/dashboard', [DashboardController::class, 'index']);

            $router->get('/templates', [TemplateController::class, 'index']);
            $router->get('/templates/create', [TemplateController::class, 'create']);
            $router->post('/templates', [TemplateController::class, 'store']);

            $router->get('/sms/send', [SmsController::class, 'create']);
            $router->post('/sms/send', [SmsController::class, 'store']);
            $router->get('/sms/history', [SmsController::class, 'history']);

            $router->get('/balance', [BalanceController::class, 'index']);
            $router->post('/balance/topup', [BalanceController::class, 'topup']);

            $router->get('/profile', [ProfileController::class, 'show']);
            $router->post('/profile', [ProfileController::class, 'update']);
            $router->post('/profile/api-keys', [ProfileController::class, 'createApiKey']);
        });

        // ---------- Admin paneli — til prefiksisiz, standart o'zbek tilida, ----------
        // ---------- oddiy foydalanuvchilardan butunlay mustaqil ----------
        $router->before('GET|POST', '/admin(/.*)?', fn () => AdminMiddleware::handle());
        $router->before('GET|POST', '/admin/login', fn () => AdminGuestMiddleware::handle());

        $router->get('/admin/login', [AdminLoginController::class, 'show']);
        $router->post('/admin/login', [AdminLoginController::class, 'login']);
        $router->get('/admin/logout', [AdminLoginController::class, 'logout']);

        $router->get('/admin', [AdminDashboardController::class, 'index']);

        $router->get('/admin/users', [UserController::class, 'index']);
        $router->get('/admin/users/(\d+)', [UserController::class, 'show']);
        $router->post('/admin/users/(\d+)/balance', [UserController::class, 'adjustBalance']);
        $router->post('/admin/users/(\d+)/toggle-status', [UserController::class, 'toggleStatus']);

        $router->get('/admin/templates', [AdminTemplateController::class, 'index']);
        $router->post('/admin/templates/(\d+)/approve', [AdminTemplateController::class, 'approve']);
        $router->post('/admin/templates/(\d+)/reject', [AdminTemplateController::class, 'reject']);

        $router->get('/admin/settings', [SettingController::class, 'index']);
        $router->post('/admin/settings', [SettingController::class, 'update']);

        $router->get('/admin/transactions', [TransactionController::class, 'index']);

        // ---------- Tashqi to'lov tizimlari webhook'lari ----------
        $router->post('/webhooks/checkout', [WebhookController::class, 'checkout']);

        // ---------- 404 ----------
        $router->set404(function () {
            http_response_code(404);
            echo View::render('errors.404');
        });
    }
}
