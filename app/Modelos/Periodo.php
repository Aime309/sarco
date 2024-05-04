<?php

namespace SARCO\Modelos;

use Stringable;

final class Periodo extends Modelo implements Stringable {
  public readonly int $inicio;

  function siguientePeriodo(): int {
    return $this->inicio + 1;
  }

  function __toString(): string {
    return "$this->inicio-{$this->siguientePeriodo()}";
  }
}
