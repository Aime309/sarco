<?php

namespace SARCOV2\Compartido\Dominio\Excepciones;

final class CorreoInvalido extends ObjetoDeValorInvalido {
  protected static function mensaje(): string {
    return 'Correo inválido';
  }
}
