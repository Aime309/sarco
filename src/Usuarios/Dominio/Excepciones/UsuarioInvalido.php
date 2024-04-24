<?php

namespace SARCOV2\Usuarios\Dominio\Excepciones;

use SARCOV2\Compartido\Dominio\Excepciones\ObjetoDeValorInvalido;

final class UsuarioInvalido extends ObjetoDeValorInvalido {
  protected static function mensaje(): string {
    return 'Usuario inválido';
  }
}
