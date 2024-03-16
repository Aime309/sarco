<?php

namespace SARCO\Middlewares;

use Leaf\Auth;
use Leaf\BareUI;
use Leaf\Http\Session;

class Autenticacion {
  static function bloquearNoAutenticados(): void {
    $cedula = Session::get('credenciales.cedula');
    $clave = Session::get('credenciales.clave');

    $usuario = Auth::login(compact('cedula', 'clave'));

    if (!$usuario) {
      exit(BareUI::render('paginas/ingreso'));
    }
  }
}
