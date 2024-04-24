<?php

namespace SARCOV2\Compartido\Dominio\Excepciones;

final class TelefonoInvalido extends ObjetoDeValorInvalido {
  protected static function mensaje(): string {
    return 'Teléfono inválido';
  }
}
