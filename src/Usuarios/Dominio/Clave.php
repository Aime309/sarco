<?php

namespace SARCOV2\Usuarios\Dominio;

use SARCOV2\Usuarios\Dominio\Excepciones\ClaveInvalida;
use Stringable;

final readonly class Clave implements Stringable {
  private const ALGORITMO = PASSWORD_DEFAULT;

  function __construct(private string $claveEncriptada) {
    self::asegurarQueEstaEncriptada($claveEncriptada);
  }

  function __toString(): string {
    return $this->claveEncriptada;
  }

  function esValida(string $clave): bool {
    return password_verify($clave, $this->claveEncriptada);
  }

  static function encriptar(string $clave): self {
    self::asegurarValidez($clave);

    return new self(password_hash($clave, self::ALGORITMO));
  }

  private static function asegurarQueEstaEncriptada(string $clave): void {
    if (!str_starts_with($clave, '$' . self::ALGORITMO)) {
      throw (new ClaveInvalida($clave))->debidoA('Clave no encriptada inválida');
    }
  }

  private static function asegurarValidez(string $claveSinEncriptar): void {
    if (!preg_match('/^(?!.*[<>]).{8,20}$/', $claveSinEncriptar)) {
      throw (new ClaveInvalida($claveSinEncriptar))->debidoA('Debe tener entre 3 y 20 letras');
    }
  }
}
