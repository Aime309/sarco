<?php

namespace SARCOV2\Compartido\Dominio;

final readonly class FechaNacimiento extends Fecha {
  protected static function asegurarValidez(int $año, int $mes, int $dia): void {
    parent::asegurarValidez($año, $mes, $dia);

    // TODO: validar parámetros
  }
}
