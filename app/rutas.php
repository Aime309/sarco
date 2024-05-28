<?php

use flight\net\Router;
use SARCO\App;
use SARCO\Controladores\ControladorDeIngreso;
use SARCO\Controladores\ControladorDeInicio;
use SARCO\Controladores\ControladorDeRespaldos;
use SARCO\Enumeraciones\Rol;

require_once __DIR__ . '/intermediarios.php';

App::group('/api', require __DIR__ . '/rutas/api.php');
App::route('GET /salir', [ControladorDeIngreso::class, 'salir']);

App::group('/registrate', require __DIR__ . '/rutas/registro-director.php', [
  permitirSiNoHayDirectoresActivos()
]);

App::post('/ingresar', [ControladorDeIngreso::class, 'autenticar']);

App::group('/', function (Router $router): void {
  $router->get('/', [ControladorDeInicio::class, 'indice']);

  $router
    ->get('respaldar', [ControladorDeRespaldos::class, 'respaldar'])
    ->addMiddleware(autorizar(Rol::Director));

  $router
    ->get('restaurar', [ControladorDeRespaldos::class, 'restaurar'])
    ->addMiddleware(autorizar(Rol::Director));

  $router->group('usuarios', require __DIR__ . '/rutas/usuarios.php');
  $router->group('representantes', require __DIR__ . '/rutas/representantes.php');
  $router->group('maestros', require __DIR__ . '/rutas/maestros.php');

  $router->group('periodos', require __DIR__ . '/rutas/periodos.php', [
    autorizar(Rol::Director)
  ]);

  $router->group('perfil', require __DIR__ . '/rutas/perfil.php');

  $router->group('salas', require __DIR__ . '/rutas/salas.php', [
    autorizar(Rol::Director, Rol::Secretario)
  ]);

  $router->group('aulas', require __DIR__ . '/rutas/aulas.php', [
    autorizar(Rol::Director, Rol::Secretario)
  ]);

  $router->group('estudiantes', require __DIR__ . '/rutas/estudiantes.php');
  $router->group('inscripciones', require __DIR__ . '/rutas/inscripciones.php');
}, [
  mostrarFormularioDeIngresoSiNoEstaAutenticado(),
  permitirUsuariosActivos(),
  notificarSiLimiteDePeriodoExcedido()
]);
