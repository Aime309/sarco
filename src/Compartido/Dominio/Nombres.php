<?php

namespace SARCOV2\Compartido\Dominio;

use Stringable;

final readonly class Nombres implements Stringable {
  private string $primerNombre;
  private ?string $segundoNombre;

  function __construct(string $primerNombre, ?string $segundoNombre = null) {
    self::asegurarValidez($primerNombre, $segundoNombre);

    $this->primerNombre = $primerNombre;
    $this->segundoNombre = $segundoNombre;
  }

  function __toString(): string {
    return $this->segundoNombre === null
      ? $this->primerNombre
      : "$this->primerNombre $this->segundoNombre";
  }

  static function instanciar(string $nombres): self {
    return new self(...explode(' ', $nombres));
  }

  private static function asegurarValidez(
    string $primerNombre,
    ?string $segundoNombre
  ): void {
    // TODO: validar parámetros
  }
}
