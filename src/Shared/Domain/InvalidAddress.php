<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

use InvalidArgumentException;

final class InvalidAddress extends InvalidArgumentException {
  function __construct(string $address) {
    $this->message = "Dirección inválida $address (Debe tener mínimo 3 caracteres,
    números)";
  }
}
