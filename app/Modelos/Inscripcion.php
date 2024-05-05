<?php

declare(strict_types=1);

namespace SARCO\Modelos;

final class Inscripcion extends Modelo {
  public readonly string $momento;
  public readonly string $nombresEstudiante;
  public readonly string $apellidosEstudiante;
  public readonly string $nombresDocente;
  public readonly string $apellidosDocente;

  function momento(): string {
    return "Momento $this->momento";
  }

  function estudiante(): string {
    return "$this->nombresEstudiante $this->apellidosEstudiante";
  }

  function docente(): string {
    return "$this->nombresDocente $this->apellidosDocente";
  }
}
