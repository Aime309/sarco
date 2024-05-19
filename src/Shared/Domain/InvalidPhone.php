<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

use InvalidArgumentException;

final class InvalidPhone extends InvalidArgumentException {
  function __construct(string $phone) {
    $this->message = "Teléfono inválido $phone (Debe tener el formato +xxYYYzzzzzzz
  o xxxxYYYzzzz)";
  }
}
