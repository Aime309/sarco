<?php

namespace SARCOV2\Usuarios\Dominio\Excepciones;

use SARCOV2\Compartido\Dominio\Excepciones\ObjetoDeValorInvalido;

final class ClaveInvalida extends ObjetoDeValorInvalido {
  protected static function mensaje(): string {
    return 'Clave inválida';
  }
}
