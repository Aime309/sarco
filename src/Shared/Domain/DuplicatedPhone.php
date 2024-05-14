<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

use RuntimeException;

final class DuplicatedPhone extends RuntimeException {
  function __construct(string $phone) {
    $this->message = "Teléfono $phone ya existe";
  }
}
