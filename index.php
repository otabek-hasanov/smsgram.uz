<?php

/*
|--------------------------------------------------------------------------
| SMSGram — yagona kirish nuqtasi (front controller)
|--------------------------------------------------------------------------
| Barcha so'rovlar shu fayl orqali o'tadi (smsgram.uz/index.php).
| Butun loyihada yagona require shu yerda — bootstrap/app.php Composer
| autoload'ni ulaydi, qolgan hamma narsa namespace orqali ishlaydi.
*/

define('SMSGRAM', __DIR__);

use App\Routes\ApiRoutes;
use App\Routes\WebRoutes;

$router = require __DIR__ . '/bootstrap/app.php';

WebRoutes::register($router);
ApiRoutes::register($router);

$router->run();
