<?php

namespace SARCOV2\Compartido\Dominio\Excepciones;

final class DireccionInvalida extends ObjetoDeValorInvalido {
  protected static function mensaje(): string {
    return 'Dirección inválida';
  }
}
