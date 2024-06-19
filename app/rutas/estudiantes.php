<?php

use flight\net\Router;
use SARCO\Controladores\ControladorDeBoletines;
use SARCO\Controladores\ControladorDeEstudiantes;
use SARCO\Controladores\ControladorDeInscripciones;
use SARCO\Enumeraciones\Rol;

return function (Router $router): void {
  $router->get('/', [ControladorDeEstudiantes::class, 'mostrarListado']);
  $router->get('/@cedula:v-\d+', [ControladorDeEstudiantes::class, 'mostrarPerfil']);

  $router
    ->get('/inscribir', [
      ControladorDeInscripciones::class,
      'mostrarFormularioDeInscripcion'
    ])
    ->addMiddleware(autorizar(Rol::Secretario));

  $router
    ->post('/inscribir', [ControladorDeInscripciones::class, 'inscribir'])
    ->addMiddleware(autorizar(Rol::Secretario));

  $router
    ->get('/boletines', [ControladorDeBoletines::class, 'mostrarListado'])
    ->addMiddleware(Rol::Secretario, Rol::Docente);

  $router->group('/boletines/@id', function (Router $router): void {
    $router->get('/', [ControladorDeBoletines::class, 'mostrarEdicion']);
    $router->post('/', [ControladorDeBoletines::class, 'actualizar']);
  }, [autorizar(Rol::Docente)]);
};
