<?php

namespace App\Core;

use Predis\Client;

defined('SMSGRAM') or die('Direct access is not allowed.');

final class Redis
{
    private static ?Client $instance = null;

    public static function getInstance(): Client
    {
        if (self::$instance === null) {
            $cfg = Config::get('redis');

            self::$instance = new Client([
                'scheme' => $cfg['scheme'],
                'host'   => $cfg['host'],
                'port'   => $cfg['port'],
                'database' => $cfg['database'] ?? 0,
            ], [
                'prefix' => $cfg['prefix'] ?? '',
            ]);
        }

        return self::$instance;
    }
}
