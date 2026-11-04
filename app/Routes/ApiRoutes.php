<?php

namespace App\Routes;

use App\Http\Controllers\Api\SmsController;
use Bramus\Router\Router;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class ApiRoutes
{
    public static function register(Router $router): void
    {
        $router->post('/api/v1/sms/send', [SmsController::class, 'send']);
        $router->get('/api/v1/balance', [SmsController::class, 'balance']);
    }
}
