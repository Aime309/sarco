<?php

use Illuminate\Container\Container;
use SARCOV2\Usuarios\Dominio\RepositorioDeUsuarios;
use SARCOV2\Usuarios\Infraestructura\RepositorioDeUsuariosPDO;
use Symfony\Component\Dotenv\Dotenv;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

session_start();
(new Dotenv())->load(__DIR__ . '/../.env');

$basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);

$whoops = new Run;
$whoops->pushHandler(new PrettyPageHandler);
$whoops->register();

$contenedor = Container::getInstance();
$contenedor->bind(RepositorioDeUsuarios::class, RepositorioDeUsuariosPDO::class, true);

$contenedor->bind(PDO::class, fn (): PDO => new PDO(
  "{$_ENV['DB_CONNECTION']}:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_DATABASE']};charset=utf8",
  $_ENV['DB_USERNAME'],
  $_ENV['DB_PASSWORD']
));

Container::setInstance($contenedor);
Flight::registerContainerHandler(fn (string $clase): object => $contenedor->get($clase));
Flight::set('flight.handle_errors', false);
Flight::set('flight.views.path', __DIR__ . '/../vistas');
Flight::set('flight.views.extension', '.view.php');
Flight::view()->set('error', @$_SESSION['error']);
Flight::view()->set('success', @$_SESSION['success']);
Flight::view()->set('template', Flight::view());

unset($_SESSION['error']);
unset($_SESSION['success']);
