<?php

namespace SARCOV2\Compartido\Dominio;

use Stringable;

final readonly class Apellidos implements Stringable {
  private string $primerApellido;
  private ?string $segundoApellido;

  function __construct(string $primerApellido, ?string $segundoApellido = null) {
    self::asegurarValidez($primerApellido, $segundoApellido);

    $this->primerApellido = $primerApellido;
    $this->segundoApellido = $segundoApellido;
  }

  function __toString(): string {
    return $this->segundoApellido === null
      ? $this->primerApellido
      : "$this->primerApellido $this->segundoApellido";
  }

  static function instanciar(string $apellidos): self {
    return new self(...explode(' ', $apellidos));
  }

  private static function asegurarValidez(
    string $primerApellido,
    ?string $segundoApellido = null
  ): void {
    // TODO: validar parámetros
  }
}
