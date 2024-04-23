<?php

namespace SARCOV2\Compartido\Dominio;

use Stringable;

final readonly class Direccion implements Stringable {
  private string $direccion;

  function __construct(string $direccion) {
    self::asegurarValidez($direccion);

    $this->direccion = $direccion;
  }

  function __toString(): string {
    return $this->direccion;
  }

  private static function asegurarValidez(string $direccion): void {
    // TODO: validar $direccion
  }
}
