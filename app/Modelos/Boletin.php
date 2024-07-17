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
  public readonly int $numeroMomento;
  private ?Momento $momento;
  private ?Estudiante $estudiante = null;

  /** @var Usuario[] */
  private array $docentes = [];

  function estudiante(): string {
    return "$this->nombresEstudiante $this->apellidosEstudiante";
  }

  function asignarEstudiante(Estudiante $estudiante): self {
    $this->estudiante = $estudiante;

    return $this;
  }

  function obtenerEstudiante(): ?Estudiante {
    return $this->estudiante;
  }

  function asignarDocentes(Usuario ...$docentes): self {
    $this->docentes = $docentes;

    return $this;
  }

  function asignarMomento(Momento $momento): self {
    $this->momento = $momento;

    return $this;
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

  function momento(): ?Momento {
    return $this->momento;
  }

  function __get(string $propiedad): mixed {
    if ($propiedad === 'momento') {
      return $this->numeroMomento;
    }
  }
}
