<?php

namespace SARCOV2\Compartido\Dominio;

use SARCOV2\Compartido\Dominio\Excepciones\FechaDeNacimientoInvalida;

final readonly class FechaNacimiento extends Fecha {
  protected function asegurarValidez(int $año, int $mes, int $dia): void {
    parent::asegurarValidez($año, $mes, $dia);

    if ($año < 1906) {
      throw new FechaDeNacimientoInvalida($this->formatear());
    }
  }
}
