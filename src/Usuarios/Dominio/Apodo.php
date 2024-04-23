<?php

namespace SARCOV2\Usuarios\Dominio;

use Stringable;

final readonly class Apodo implements Stringable {
  private string $apodo;

  function __construct(string $apodo) {
    self::asegurarValidez($apodo);

    $this->apodo = $apodo;
  }

  function __toString(): string {
    return $this->apodo;
  }

  private static function asegurarValidez(string $apodo): void {
    // TODO: validar $apodo
  }
}
