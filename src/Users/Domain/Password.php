<?php

declare(strict_types=1);

namespace SARCO\Users\Domain;

final readonly class Password {
  function __construct(public string $value) {
    $this->validate();
  }

  private function validate(): void {
    if (!preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*\W).{8,}$/', $this->value)) {
      throw new InvalidPassword;
    }
  }
}
