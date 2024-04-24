<?php

namespace SARCO\Mediadores;

use Flight;
use Illuminate\Container\Container;
use SARCOV2\Compartido\Dominio\Cedula;
use SARCOV2\Usuarios\Dominio\RepositorioDeUsuarios;
use SARCOV2\Usuarios\Dominio\UsuarioNoExiste;
use TypeError;

final class AseguraQueElUsuarioEstaAutenticado {
  static function before(): void {
    $cedula = @$_SESSION['credenciales.cedula'];
    $repositorio = Container::getInstance()->get(RepositorioDeUsuarios::class);

    try {
      Flight::view()->set('usuario', $repositorio->encontrarPorCedula(new Cedula($cedula)));
    } catch (UsuarioNoExiste | TypeError) {
      Flight::redirect('/ingresar');
    }
  }
}
