<?php

use flight\net\Router;
use SARCO\App;
use SARCO\Controladores\ControladorDeAulas;
use SARCO\Modelos\Aula;
use Symfony\Component\Uid\UuidV4;

return function (Router $router): void {
  $router->get('/', [ControladorDeAulas::class, 'mostrarListado']);
  $router->post('/', [ControladorDeAulas::class, 'aperturar']);

  $router->get(
    '/nueva',
    [ControladorDeAulas::class, 'mostrarFormularioParaAperturar']
  );

  $router->group('/@codigo', function (Router $router): void {
    $router->get('/', [ControladorDeAulas::class, 'mostrarEdicion']);
    $router->post('/', [ControladorDeAulas::class, 'actualizar']);
    $router->get('/eliminar', [ControladorDeAulas::class, 'eliminar']);
  });
};
