<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

use InvalidArgumentException;

class InvalidNames extends InvalidArgumentException {
  function __construct(string $names) {
    $this->message = "Nombres inválidos $names (Deben ser mínimo 1 palabra,
  máximo 2 palabras)";
  }
}
