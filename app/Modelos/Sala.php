<?php

namespace SARCO\Modelos;

use Stringable;

final class Sala extends Modelo implements Stringable {
  public readonly string $nombre;
  public readonly int $edadMinima;
  public readonly int $edadMaxima;
  public readonly bool $estaActiva;
  public ?Aula $aula = null;
  /** @var Maestro[] */
  private array $docentes = [];

  function asignarDocentes(Maestro ...$docentes): self {
    $this->docentes = $docentes;

    return $this;
  }

  /** @return Maestro[] */
  function docentes(): array {
    return $this->docentes;
  }

  function __toString(): string {
    return $this->nombre;
  }
}
