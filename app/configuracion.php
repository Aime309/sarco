<?php

use Leaf\Auth;
use Leaf\BareUI;
use Symfony\Component\Dotenv\Dotenv;

(new Dotenv())->load(__DIR__ . '/../.env');

$basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);

Auth::config('ID_KEY', 'id');
Auth::config('PASSWORD_KEY', 'clave');
Auth::config('DB_TABLE', 'usuarios');
Auth::config('GUARD_LOGIN', "$basePath/ingreso");
Auth::config('GUARD_REGISTER', "$basePath/registrate");
Auth::config('GUARD_HOME', $basePath);
Auth::config('GUARD_LOGOUT', "$basePath/salir");

$dsn = "{$_ENV['DB_CONNECTION']}:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_DATABASE']};charset=utf8";

Auth::dbConnection(new PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']));
Auth::useSession();

BareUI::config('path', __DIR__ . '/../vistas');
