<?php

namespace SARCOV2\Compartido\Dominio;

use Stringable;

final readonly class Correo implements Stringable {
  private string $correo;

  function __construct(string $correo) {
    self::asegurarValidez($correo);

    $this->correo = $correo;
  }

  function __toString(): string {
    return $this->correo;
  }

  private static function asegurarValidez(string $correo): void {
    // TODO: validar $correo
  }
}
