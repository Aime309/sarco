<?php

namespace SARCOV2\Usuarios\Aplicacion;

use SARCOV2\Usuarios\Dominio\{RepositorioDeUsuarios, Usuarios};

final readonly class ConsultadorDeUsuarios {
  function __construct(private RepositorioDeUsuarios $repositorio) {
  }

  function obtenerTodos(): Usuarios {
    return $this->repositorio->obtenerTodos();
  }
}
