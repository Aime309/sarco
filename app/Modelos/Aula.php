<?php

namespace SARCO\Modelos;

use Stringable;

final class Aula extends Modelo implements Stringable {
  public readonly string $codigo;
  public readonly string $tipo;

  function capacidad(): string {
    if (strtolower($this->tipo) === 'pequeña') {
      return '28-29';
    }

    return '31-32';
  }

  function esPequeña(): bool {
    return $this->tipo === 'Pequeña';
  }

  function __toString(): string {
    return $this->codigo;
  }
}
