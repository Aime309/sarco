<?php

namespace SARCOV2\Compartido\Dominio;

use Stringable;

final readonly class Cedula implements Stringable {
  private int $cedula;

  function __construct(int $cedula) {
    self::asegurarValidez($cedula);

    $this->cedula = $cedula;
  }

  function __toString(): string {
    return $this->cedula;
  }

  private static function asegurarValidez(int $cedula): void {
    // TODO: validar $cedula
  }
}
