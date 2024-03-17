<?php

namespace SARCO\Middlewares;

use Leaf\Auth;
use Leaf\Http\Session;

class Autenticacion {
  static function bloquearNoAutenticados(): void {
    $cedula = Session::get('credenciales.cedula');
    $clave = Session::get('credenciales.clave');

    $usuario = Auth::login(compact('cedula', 'clave'));

    if (!$usuario) {
      renderizar('ingreso', 'Ingreso');
    }
  }

  static function redirigeAlRegistroSiNoHayUsuarios(): void {
    $cantidadDeUsuarios = db()->select('usuarios')->count();

    if (!$cantidadDeUsuarios) {
      renderizar('registro', 'Regístrate');
    }
  }
}
