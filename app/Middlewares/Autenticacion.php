<?php

namespace SARCO\Middlewares;

use DateTime;
use DateTimeImmutable;
use Leaf\Auth;
use Leaf\Http\Session;
use SARCO\Modelos\Rol;
use SARCO\Modelos\Usuario;

class Autenticacion {
  static function bloquearNoAutenticados(): void {
    $cedula = Session::get('credenciales.cedula');
    $clave = Session::get('credenciales.clave');

    $usuario = Auth::login(compact('cedula', 'clave'));

    if (!$usuario) {
      renderizar('ingreso', 'Ingreso');
    }

    $GLOBALS['usuario'] = new Usuario(
      $usuario['user']['nombre'],
      $usuario['user']['apellido'],
      $usuario['user']['cedula'],
      Rol::from($usuario['user']['id_rol']),
      new DateTimeImmutable($usuario['user']['created_at']),
      new DateTime($usuario['user']['updated_at'])
    );
  }

  static function redirigeAlRegistroSiNoHayUsuarios(): void {
    $cantidadDeUsuarios = db()->select('usuarios')->count();

    if (!$cantidadDeUsuarios) {
      renderizar('registro', 'Regístrate');
    }
  }
}
