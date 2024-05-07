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

  function estudiante(): string {
    return "$this->nombresEstudiante $this->apellidosEstudiante";
  }

  function momento(): string {
    return "Momento $this->momento";
  }
}
