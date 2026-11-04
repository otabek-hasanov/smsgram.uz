<?php

defined('SMSGRAM') or die('Direct access is not allowed.');

/*
|--------------------------------------------------------------------------
| Bootstrap
|--------------------------------------------------------------------------
| Butun ilova bo'ylab ishlatiladigan yagona require shu yerda —
| Composer autoload'ni ulash uchun. Qolgan barcha klasslar PSR-4
| namespace orqali avtomatik yuklanadi (use App\...;).
*/

require SMSGRAM . '/vendor/autoload.php';

use App\Core\Application;

Application::boot();

return Application::router();
