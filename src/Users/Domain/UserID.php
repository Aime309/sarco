<?php

declare(strict_types=1);

namespace SARCO\Users\Domain;

use Stringable;

final readonly class UserID implements Stringable {
  public string $value;

  function __construct(string $value) {
    $this->value = $value;
  }

  function __toString(): string {
    return $this->value;
  }
}
