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
    ->addMiddleware(autorizar(Rol::Secretario, Rol::Director));

  $router
    ->post('/inscribir', [ControladorDeInscripciones::class, 'inscribir'])
    ->addMiddleware(autorizar(Rol::Secretario, Rol::Director));

  $router
    ->get('/boletines', [ControladorDeBoletines::class, 'mostrarListado']);

  $router->group('/boletines/@id', function (Router $router): void {
    $router
      ->get('/editar', [ControladorDeBoletines::class, 'mostrarEdicion'])
      ->addMiddleware(permitirEditarBoletinesSoloDelDocenteAutenticado());
    $router
      ->post('/', [ControladorDeBoletines::class, 'actualizar'])
      ->addMiddleware(permitirEditarBoletinesSoloDelDocenteAutenticado());

    $router->get('/', [ControladorDeBoletines::class, 'imprimir']);
  }, [autorizar(Rol::Docente, Rol::Director)]);
};
