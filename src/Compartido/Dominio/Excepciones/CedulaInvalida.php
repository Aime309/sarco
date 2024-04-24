<?php

namespace SARCOV2\Compartido\Dominio\Excepciones;

final class CedulaInvalida extends ObjetoDeValorInvalido {
  protected static function mensaje(): string {
    return 'Cédula inválida';
  }
}
