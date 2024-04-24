<?php

namespace SARCOV2\Compartido\Dominio\Excepciones;

final class FechaDeNacimientoInvalida extends FechaInvalida {
  protected static function mensaje(): string {
    return 'Fecha de nacimiento inválida';
  }
}
