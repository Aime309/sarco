<?php

use Leaf\{Auth, BareUI, Db, Form, Router};
use Symfony\Component\Dotenv\Dotenv;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

(new Dotenv())->load(__DIR__ . '/../.env');

$basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);

function db(): Db {
  static $db = null;

  if (!$db) {
    $dsn = "{$_ENV['DB_CONNECTION']}:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_DATABASE']};charset=utf8";
    $pdo = new PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
    $db = new Db;
    $db->connection($pdo);
  }

  return $db;
}

Auth::config('ID_KEY', 'id');
Auth::config('PASSWORD_KEY', 'clave');
Auth::config('DB_TABLE', 'usuarios');
Auth::config('GUARD_LOGIN', "$basePath/ingreso");
Auth::config('GUARD_REGISTER', "$basePath/registrate");
Auth::config('GUARD_HOME', $basePath);
Auth::config('GUARD_LOGOUT', "$basePath/salir");
Auth::dbConnection(db()->connection());

BareUI::config('path', __DIR__ . '/../vistas');
Router::setBasePath($basePath);

Form::rule('textonly', '/^[a-zA-ZáÁéÉíÍóÓúÚñÑ]+$/', '{Field} sólo puede contener letras');
Form::message('alphadash', '{Field} sólo puede contener letras, números, guión (-) y guión bajo (_)');

$whoops = new Run;
$whoops->pushHandler(new PrettyPageHandler);
$whoops->register();
