<?php

declare(strict_types=1);

namespace SARCO\Modelos;

final class Inscripcion extends Modelo {
  public readonly int $periodo;
  public readonly string $nombresEstudiante;
  public readonly string $apellidosEstudiante;

  function periodo(): string {
    return "$this->periodo-" . ($this->periodo + 1);
  }

  function estudiante(): string {
    return "$this->nombresEstudiante $this->apellidosEstudiante";
  }
}
