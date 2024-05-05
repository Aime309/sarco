<?php

use SARCO\App;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

ini_set('error_log', __DIR__ . '/../logs/errores.log');
App::set('flight.handle_errors', false);
App::set('flight.log_errors', true);

if ($_ENV['IS_DEBUG']) {
  $whoops = new Run;
  $whoops->pushHandler(new PrettyPageHandler);
  $whoops->register();

  App::map('notFound', function (): void {
    error_log(PHP_EOL . json_encode(
      App::request(),
      JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
    ));

    App::render('paginas/errores/404', [], 'pagina');
    App::render('plantillas/errores', ['titulo' => 'Página no encontrada']);
    App::response()->status(404)->send();
  });
} else {
  App::map('error', function (): void {
    App::render('paginas/errores/500', [], 'pagina');
    App::render('plantillas/errores', ['titulo' => 'Error interno']);
    App::response()->status(500)->send();
  });

}

