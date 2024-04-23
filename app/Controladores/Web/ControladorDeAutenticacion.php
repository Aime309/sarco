<?php

namespace SARCO\Controladores\Web;

use Leaf\Auth;
use Leaf\Form;
use Leaf\Http\Request;
use Leaf\Http\Session;
use Leaf\Router;

final readonly class ControladorDeAutenticacion {
  static function procesarCredenciales() {
    $credenciales = Request::validate([
      'cedula' => 'number',
      'clave' => 'alphadash'
    ]);

    if ($errors = Form::errors()) {
      @$errors['cedula'] && Session::set('error', $errors['cedula'][0]);
      @$errors['clave'] && Session::set('error', $errors['clave'][0]);

      return Router::push('./');
    }

    $usuario = Auth::login($credenciales);

    if (!$usuario) {
      Session::set('error', 'Cédula o contraseña incorrecta');

      return Router::push('./');
    }

    Session::set('credenciales.cedula', $usuario['user']['cedula']);
    Session::set('credenciales.clave', $credenciales['clave']);
    Router::push('./');
  }
}
