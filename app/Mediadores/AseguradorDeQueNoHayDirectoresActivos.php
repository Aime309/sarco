<?php

namespace SARCO\Mediadores;

use Leaf\Router;
use SARCOV2\Usuarios\Dominio\RepositorioDeUsuarios;
use SARCOV2\Usuarios\Dominio\Rol;

final readonly class AseguradorDeQueNoHayDirectoresActivos {
  function __construct(RepositorioDeUsuarios $repositorio) {
    $directores = $repositorio->obtenerTodosPorRol(Rol::Director);

    if ($directores->hayActivos()) {
      @session_start();
      $_SESSION['error'] ??= 'Ya hay al menos 1 director activo';

      return Router::push('./');
    }
  }
}
