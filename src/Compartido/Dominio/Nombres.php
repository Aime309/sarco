<?php

namespace SARCOV2\Compartido\Dominio;

use SARCOV2\Compartido\Dominio\Excepciones\NombresInvalidos;
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
    return mb_convert_case(
      $this->segundoNombre === null
        ? $this->primerNombre
        : "$this->primerNombre $this->segundoNombre",
      MB_CASE_TITLE
    );
  }

  static function instanciar(string $nombres): self {
    return new self(...explode(' ', $nombres));
  }

  private static function asegurarValidez(
    string $primerNombre,
    ?string $segundoNombre = null
  ): void {
    $nombres = "$primerNombre $segundoNombre";
    $excepcion = new NombresInvalidos("$primerNombre $segundoNombre");

    if (
      strlen($nombres) < 3
      || strlen($nombres) > 40
      || !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚ]{3,20}(\s?|\s?[a-zA-ZáéíóúÁÉÍÓÚ]{3,20})$/', $nombres)
    ) {
      throw $excepcion->debidoA('Debe tener entre 3 y 40 letras');
    }
  }
}
