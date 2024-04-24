<?php

namespace SARCOV2\Compartido\Dominio;

use SARCOV2\Compartido\Dominio\Excepciones\DireccionInvalida;
use Stringable;

final readonly class Direccion implements Stringable {
  private string $direccion;

  function __construct(string $direccion) {
    self::asegurarValidez($direccion);

    $this->direccion = $direccion;
  }

  function __toString(): string {
    return strtoupper($this->direccion[0]) . substr($this->direccion, 1);
  }

  private static function asegurarValidez(string $direccion): void {
    if (!preg_match('/^(?!.*[<>]).{3,}$/', $direccion)) {
      throw (new DireccionInvalida($direccion))->debidoA('Debe tener al menos 3 letras');
    }
  }
}
