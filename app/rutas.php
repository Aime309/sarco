<?php

use Leaf\Auth;
use Leaf\Form;
use Leaf\Http\Request;
use Leaf\Http\Session;
use Leaf\Router;
use SARCO\Middlewares\Autenticacion;
use SARCO\Middlewares\Mensajes;

Router::all('/salir', function (): void {
  Auth::logout('./');
});

Router::post('/ingresar', function (): void {
  $credenciales = Request::validate([
    'cedula' => 'number',
    'clave' => 'alphadash'
  ]);

  if ($errors = Form::errors()) {
    @$errors['cedula'] && Session::set('error', $errors['cedula'][0]);
    @$errors['clave'] && Session::set('error', $errors['clave'][0]);

    exit(Router::push('./'));
  }

  $usuario = Auth::login($credenciales);

  if (!$usuario) {
    Session::set('error', 'Cédula o contraseña incorrecta');
    exit(Router::push('./'));
  }

  Session::set('credenciales.cedula', $usuario['user']['cedula']);
  Session::set('credenciales.clave', $credenciales['clave']);
  exit(Router::push('./'));
});

Router::group('/registrate', ['middleware' => [Mensajes::class, 'capturarMensajes'], function (): void {
  Router::get('/', function (): void {
    Auth::register([
      'nombre' => 'Franyer',
      'apellido' => 'Sánchez',
      'cedula' => 28072391,
      'clave' => 1234,
      'id_rol' => 1
    ]);
  });
}]);

Router::group(
  '/',
  ['middleware' => function (): void {
    Mensajes::capturarMensajes();
    Autenticacion::bloquearNoAutenticados();
  }, function (): void {
    Router::get('/', function (): void {
    });
  }]
);
