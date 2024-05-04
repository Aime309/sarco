<?php

namespace SARCO\Modelos;

use Stringable;

/**
 * @property-read int $periodo
 */
final class Momento extends Modelo implements Stringable {
  public readonly int $numero;
  public readonly int $mesInicio;
  public readonly int $diaInicio;
  public readonly int $idPeriodo;

  function inicio(): string {
    return "$this->diaInicio/$this->mesInicio";
  }

  function __toString(): string {
    return 'Momento ' . $this->numero;
  }
}
