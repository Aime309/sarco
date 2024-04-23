<?php

namespace SARCO\Mediadores;

use Leaf\Router;
use SARCOV2\Usuarios\Dominio\RepositorioDeUsuarios;
use SARCOV2\Usuarios\Dominio\Rol;

final readonly class AseguradorDeQueNoHayDirectoresActivos {
  function __construct(private RepositorioDeUsuarios $repositorio) {
  }

  function __invoke() {
    $directores = $this->repositorio->obtenerTodosPorRol(Rol::Director);

    if ($directores->count() > 0 && $directores->hayDirectoresActivos()) {
      return Router::push('./ingresar');
    }

    renderizar('registro', 'Regístrate');
  }
}
