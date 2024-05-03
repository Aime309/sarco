<?php

namespace SARCO\Modelos;

use DateTimeImmutable;

abstract class Modelo {
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
