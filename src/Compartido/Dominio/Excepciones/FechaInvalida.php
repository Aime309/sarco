<?php

namespace SARCOV2\Compartido\Dominio\Excepciones;

class FechaInvalida extends ObjetoDeValorInvalido {
  protected static function mensaje(): string {
    return 'Fecha inválida';
  }
}
