<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

use RuntimeException;

final class DuplicatedEmail extends RuntimeException {
  function __construct(string $email) {
    $this->message = "Correo $email ya existe";
  }
}
