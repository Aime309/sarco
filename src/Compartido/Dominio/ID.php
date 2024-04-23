<?php

namespace SARCOV2\Compartido\Dominio;

use Stringable;

final readonly class ID implements Stringable {
  private string $id;

  function __construct(string $id) {
    $this->id = $id;
  }

  function __toString(): string {
    return $this->id;
  }

  static function crearAleatorio(): self {
    return new self(rand());
  }
}
