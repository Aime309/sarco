<?php

use Illuminate\Container\Container;
use Leaf\{BareUI, Form, Router};
use Psr\Container\ContainerInterface;
use SARCOV2\Usuarios\Dominio\RepositorioDeUsuarios;
use SARCOV2\Usuarios\Infraestructura\RepositorioDeUsuariosPDO;
use Symfony\Component\Dotenv\Dotenv;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

(new Dotenv())->load(__DIR__ . '/../.env');

$basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);

function contenedor(): ContainerInterface {
  static $contenedor = null;

  if (!$contenedor) {
    $contenedor = Container::getInstance();

    $contenedor->bind(PDO::class, fn (): PDO => new PDO(
      "{$_ENV['DB_CONNECTION']}:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_DATABASE']};charset=utf8",
      $_ENV['DB_USERNAME'],
      $_ENV['DB_PASSWORD']
    ));
    $contenedor->bind(RepositorioDeUsuarios::class, RepositorioDeUsuariosPDO::class, true);
  }

  return $contenedor;
}

BareUI::config('path', __DIR__ . '/../vistas');
Router::setBasePath($basePath);

Form::rule('textonly', '/^[a-zA-ZáÁéÉíÍóÓúÚñÑ]+$/', '{Field} sólo puede contener letras');
Form::message('alphadash', '{Field} sólo puede contener letras, números, guión (-) y guión bajo (_)');

$whoops = new Run;
$whoops->pushHandler(new PrettyPageHandler);
$whoops->register();

session_start();
