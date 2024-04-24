<?php

namespace SARCOV2\Compartido\Dominio;

use SARCOV2\Compartido\Dominio\Excepciones\CorreoInvalido;
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
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
      throw new CorreoInvalido($correo);
    }
  }
}
