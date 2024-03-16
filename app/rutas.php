<?php

use Leaf\Auth;
use Leaf\Http\Request;
use Leaf\Http\Response;
use Leaf\Http\Session;
use Leaf\Router;
use SARCO\Middlewares\Autenticacion;

Router::post('/ingresar', function (): void {
  $credentials = Request::validate([
    'cedula' => 'number',
    'clave' => 'alphadash'
  ]);

  $user = Auth::login($credentials);

  if (!$user) {
    Session::set('error', 'Usuario o contraseña incorrecta');
    exit(Router::push('./'));
  }

  (new Response)->json($user);
});

Router::group('/', ['middleware' => [Autenticacion::class, 'bloquearNoAutenticados'], function (): void {
  Router::get('/', function (): void {
  });
}]);
