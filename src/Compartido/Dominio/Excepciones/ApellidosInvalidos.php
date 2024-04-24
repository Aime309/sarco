<?php

namespace SARCOV2\Compartido\Dominio\Excepciones;

final class ApellidosInvalidos extends ObjetoDeValorInvalido {
  protected static function mensaje(): string {
    return 'Apellidos inválidos';
  }
}
