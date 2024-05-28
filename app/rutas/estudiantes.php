<?php

use flight\net\Router;
use SARCO\Controladores\ControladorDeBoletines;
use SARCO\Controladores\ControladorDeEstudiantes;
use SARCO\Enumeraciones\Rol;

return function (Router $router): void {
  $router->get('/', [ControladorDeEstudiantes::class, 'indice']);

  $router
    ->get('/inscribir', [ControladorDeEstudiantes::class, 'crear'])
    ->addMiddleware(autorizar(Rol::Secretario));

  $router
    ->post('/inscribir', [ControladorDeEstudiantes::class, 'almacenar'])
    ->addMiddleware(autorizar(Rol::Secretario));

  $router
    ->get('/boletines', [ControladorDeBoletines::class, 'indice'])
    ->addMiddleware(Rol::Secretario, Rol::Docente);

  $router->group('/boletines/@id', function (Router $router): void {
    $router->get('/', [ControladorDeBoletines::class, 'editar']);
    $router->post('/', [ControladorDeBoletines::class, 'actualizar']);
  }, [autorizar(Rol::Docente)]);
};
