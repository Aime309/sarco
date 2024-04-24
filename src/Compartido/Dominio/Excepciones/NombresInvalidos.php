<?php

namespace SARCOV2\Compartido\Dominio\Excepciones;

final class NombresInvalidos extends ObjetoDeValorInvalido {
  protected static function mensaje(): string {
    return 'Nombres inválidos';
  }
}
