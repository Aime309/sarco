<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

use RuntimeException;

final class DuplicatedPhone extends RuntimeException {
  function __construct(string $phone) {
    parent::__construct();

    $this->message = "Teléfono $phone ya existe";
  }
}
