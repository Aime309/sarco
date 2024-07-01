<?php

namespace SARCO\Modelos;

use Stringable;

final class Periodo extends Modelo implements Stringable {
  public readonly int $inicio;
  public bool $sePuedeEliminar = true;

  /** @var Momento[] */
  private array $momentos;

  function siguientePeriodo(): int {
    return $this->inicio + 1;
  }

  function asignarMomentos(Momento ...$momentos): void {
    $this->momentos = $momentos;

    foreach ($this->momentos as $momento) {
      $momento->asignarPeriodo($this);
    }
  }

  function momento(int $numero): ?Momento {
    $momento = $this->momentos[$numero - 1] ?? null;

    return $momento;
  }

  function __toString(): string {
    return "$this->inicio-{$this->siguientePeriodo()}";
  }
}
