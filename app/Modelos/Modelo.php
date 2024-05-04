<?php

namespace SARCO\Modelos;

use DateTimeImmutable;
use stdClass;

abstract class Modelo extends stdClass {
  public int $id;
  public string $fechaRegistro;

  function fechaRegistro(string $formato = 'd/m/Y'): string {
    $fechaRegistro = DateTimeImmutable::createFromFormat(
      'Y-m-d H:i:s',
      $this->fechaRegistro
    );

    return $fechaRegistro->format($formato);
  }
}
