<?php

declare(strict_types=1);

namespace SARCO\Modelos;

final class Boletin extends Modelo {
  public readonly int $inasistencias;
  public readonly string $proyecto;
  public readonly string $descripcionFormacion;
  public readonly string $descripcionAmbiente;
  public readonly string $recomendaciones;
  public readonly string $nombresEstudiante;
  public readonly string $apellidosEstudiante;
  public readonly string $cedulaEstudiante;
  public readonly string $momento;

  /** @var Usuario[] */
  private array $docentes = [];

  function estudiante(): string {
    return "$this->nombresEstudiante $this->apellidosEstudiante";
  }

  function asignarDocentes(Usuario ...$docentes): void {
    $this->docentes = $docentes;
  }

  /** @return Usuario[] */
  function docentes(): array {
    return $this->docentes;
  }

  function puedeSerEditadoPor(Usuario $docente): bool {
    foreach ($this->docentes as $docenteIterado) {
      if ($docenteIterado->id === $docente->id) {
        return true;
      }
    }

    return false;
  }

  function momento(): string {
    return "Momento $this->momento";
  }
}
