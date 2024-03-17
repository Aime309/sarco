<?php

use Leaf\Auth;
use Leaf\Form;
use Leaf\Http\Request;
use Leaf\Http\Response;
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

Router::post('/registrate', function (): void {
  $info = Request::validate([
    'nombre' => 'textonly',
    'apellido' => 'textonly',
    'cedula' => 'number',
    'clave' => 'alphadash',
    'id_rol' => 'number'
  ]);

  if ($errors = Form::errors()) {
    @$errors['nombre'] && Session::set('error', $errors['nombre'][0]);
    @$errors['apellido'] && Session::set('error', $errors['apellido'][0]);
    @$errors['cedula'] && Session::set('error', $errors['cedula'][0]);
    @$errors['clave'] && Session::set('error', $errors['clave'][0]);
    @$errors['id_rol'] && Session::set('error', $errors['id_rol'][0]);

    exit(Router::push('./'));
  }

  Auth::register($info);
  Session::set('success', 'Cuenta creada exitósamente');
  Router::push('./');
});

Router::group(
  '/',
  ['middleware' => function (): void {
    Mensajes::capturarMensajes();
    Autenticacion::redirigeAlRegistroSiNoHayUsuarios();
    Autenticacion::bloquearNoAutenticados();
  }, function (): void {
    Router::get('/', function (): void {
      $cantidadDeUsuarios = db()->select('usuarios')->count();

      renderizar('inicio', 'Inicio', 'principal', compact('cantidadDeUsuarios'));
    });
  }]
);

Router::set404(function (): void {
  Mensajes::capturarMensajes();
  renderizar('404', '404 ~ No encontrado', 'errores');
});
