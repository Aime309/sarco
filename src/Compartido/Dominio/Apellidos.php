<?php

namespace SARCOV2\Compartido\Dominio;

use SARCOV2\Compartido\Dominio\Excepciones\ApellidosInvalidos;
use Stringable;

final readonly class Apellidos implements Stringable {
  function __construct(
    private string $primerApellido,
    private ?string $segundoApellido = null
  ) {
    self::asegurarValidez($primerApellido, $segundoApellido);
  }

  function __toString(): string {
    return mb_convert_case(
      $this->segundoApellido === null
        ? $this->primerApellido
        : "$this->primerApellido $this->segundoApellido",
      MB_CASE_TITLE
    );
  }

  static function instanciar(string $apellidos): self {
    return new self(...explode(' ', $apellidos));
  }

  private static function asegurarValidez(
    string $primerApellido,
    ?string $segundoApellido = null
  ): void {
    $apellidos = "$primerApellido $segundoApellido";
    $excepcion = new ApellidosInvalidos("$primerApellido $segundoApellido");

    if (
      strlen($apellidos) < 3
      || strlen($apellidos) > 40
      || !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚ]{3,20}(\s?|\s?[a-zA-ZáéíóúÁÉÍÓÚ]{3,20})$/', $apellidos)
    ) {
      throw $excepcion->debidoA('Debe tener entre 3 y 40 letras');
    }
  }
}
