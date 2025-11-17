<?php

session_start();

include __DIR__ . '/../vendor/autoload.php';

use App\Routing\Router;
use App\Database\Install;

$install = new Install([
    'host' => 'localhost',
    'user' => 'root',
    'password' => null,
    'database' => 'mysql',
]);

$router = new Router();
$router->handle();