<?php

use flight\net\Router;
use SARCO\Controladores\ControladorDeUsuarios;
use SARCO\Enumeraciones\Rol;

return function (Router $router): void {
  $router->get('/', [ControladorDeUsuarios::class, 'mostrarListado']);

  $router
    ->post('/', [ControladorDeUsuarios::class, 'crearCuenta'])
    ->addMiddleware(autorizar(Rol::Director, Rol::Secretario));

  $router
    ->get('/nuevo', [
      ControladorDeUsuarios::class,
      'mostrarFormularioDeRegistro'
    ])
    ->addMiddleware(autorizar(Rol::Director, Rol::Secretario));

  $router->group('/@cedula:[0-9]{7,8}', function (Router $router): void {
    $router->get('/activar', [ControladorDeUsuarios::class, 'activar']);
    $router->get('/desactivar', [ControladorDeUsuarios::class, 'desactivar']);
  }, [autorizar(Rol::Director)]);
};
