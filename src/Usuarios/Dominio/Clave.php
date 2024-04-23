<?php

namespace SARCOV2\Usuarios\Dominio;

use Stringable;

final readonly class Clave implements Stringable {
  private string $claveEncriptada;
  private const ALGORITMO = PASSWORD_DEFAULT;

  function __construct(string $claveEncriptada) {
    self::asegurarQueEstaEncriptada($claveEncriptada);

    $this->claveEncriptada = $claveEncriptada;
  }

  function __toString(): string {
    return $this->claveEncriptada;
  }

  static function encriptar(string $clave): self {
    self::asegurarValidez($clave);

    return new self(password_hash($clave, self::ALGORITMO));
  }

  private static function asegurarQueEstaEncriptada(string $clave): void {
    // TODO: verificar si $clave está encriptada
  }

  private static function asegurarValidez(string $claveSinEncriptar): void {
    // TODO: validar $claveSinEncriptar
  }
}
