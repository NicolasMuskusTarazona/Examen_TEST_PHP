<?php

namespace App\Infrastructure\Database;

use Exception;
use Illuminate\Database\Capsule\Manager as Capsule;
use Dotenv\Dotenv;

class Connection
{
    public static function init(): string|bool
    {
        // solo si aún no se han cargado
        if (!isset($_ENV['DB_HOST'])) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
            $dotenv->load();
        }

        $capsule = new Capsule;
        $capsule->addConnection([
            'driver' => 'mysql',
            'host' => $_ENV['DB_HOST'],
            'database' => $_ENV['DB_DATABASE'],
            'username' => $_ENV['DB_USERNAME'],
            'password' => $_ENV['DB_PASSWORD'],
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => ''
        ]);
        
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        try {
            Capsule::connection()->getPdo();
            return true;
        } catch (Exception $ex) {
            return "No se puede conectar con la base de datos: " . $ex->getMessage();
        }
    }
}
