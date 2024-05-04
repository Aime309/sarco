<?php

namespace SARCO\Modelos;

use Stringable;

final class Sala extends Modelo implements Stringable {
  public readonly string $nombre;
  public readonly int $edadMinima;
  public readonly int $edadMaxima;
  public readonly bool $estaActiva;

  function __toString(): string {
    return $this->nombre;
  }
}
