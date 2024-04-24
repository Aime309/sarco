<?php

namespace SARCOV2\Compartido\Dominio\Excepciones;

use InvalidArgumentException;

abstract class ObjetoDeValorInvalido extends InvalidArgumentException {
  function __construct(string $valor) {
    parent::__construct(static::mensaje() . ": $valor");
  }

  protected abstract static function mensaje(): string;

  function debidoA(string $causa): static {
    $this->message .= " ($causa)";

    return $this;
  }
}
