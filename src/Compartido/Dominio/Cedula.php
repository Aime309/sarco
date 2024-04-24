<?php

namespace SARCOV2\Compartido\Dominio;

use SARCOV2\Compartido\Dominio\Excepciones\CedulaInvalida;
use Stringable;

final readonly class Cedula implements Stringable {
  function __construct(private int $cedula) {
    self::asegurarValidez($cedula);
  }

  function __toString(): string {
    return $this->cedula;
  }

  private static function asegurarValidez(int $cedula): void {
    if ($cedula < 1_000_000 || $cedula > 99_999_999) {
      throw new CedulaInvalida($cedula);
    }
  }
}
