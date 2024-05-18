<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

use RuntimeException;

final class DuplicatedEmail extends RuntimeException {
  function __construct(string $email) {
    parent::__construct();

    $this->message = "Correo $email ya existe";
  }
}
