<?php

namespace SARCO\Mediadores;

use Flight;
use SARCOV2\Usuarios\Dominio\Rol;
use SARCOV2\Usuarios\Dominio\Usuario;

final class Autorizador {
  static function autorizarRoles(Rol ...$roles): callable {
    return static function () use ($roles) {
      $usuario = Flight::view()->get('usuario');
      assert($usuario instanceof Usuario);

      foreach ($roles as $rol) {
        if ($usuario->rol() === $rol) {
          return;
        }
      }

      $_SESSION['error'] = 'Acceso denegado';
      Flight::redirect('/');
    };
  }
}
