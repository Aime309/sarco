<?php

namespace SARCO\Mediadores;

use Leaf\Router;
use SARCOV2\Compartido\Dominio\Cedula;
use SARCOV2\Usuarios\Dominio\RepositorioDeUsuarios;
use SARCOV2\Usuarios\Dominio\UsuarioNoExiste;
use TypeError;

final readonly class AseguradorDeQueElUsuarioEstaAutenticado {
  function __construct(RepositorioDeUsuarios $repositorio) {
    @session_start();

    $cedula = @$_SESSION['credenciales.cedula'];

    try {
      $GLOBALS['usuario'] = $repositorio->encontrarPorCedula(new Cedula($cedula));
    } catch (UsuarioNoExiste | TypeError) {
      $_SESSION['error'] ??= 'Acceso denegado';
      Router::push('./ingresar');
    }
  }
}
