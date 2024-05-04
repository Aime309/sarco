<?php

namespace SARCO\Modelos;

use Stringable;

final class Momento extends Modelo implements Stringable {
  public readonly int $numero;
  public readonly int $mesInicio;
  public readonly int $diaInicio;
  public readonly int $idPeriodo;

  function __toString(): string {
    return 'Momento ' . $this->numero;
  }
}
