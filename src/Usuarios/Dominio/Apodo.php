<?php

namespace SARCOV2\Usuarios\Dominio;

use SARCOV2\Usuarios\Dominio\Excepciones\UsuarioInvalido;
use Stringable;

final readonly class Apodo implements Stringable {
  function __construct(private string $apodo) {
    self::asegurarValidez($apodo);
  }

  function __toString(): string {
    return $this->apodo;
  }

  private static function asegurarValidez(string $apodo): void {
    if (!preg_match('/^(?!.*[<>]).{3,20}$/', $apodo)) {
      throw (new UsuarioInvalido($apodo))->debidoA('Debe tener entre 3 y 20 letras');
    }
  }
}
