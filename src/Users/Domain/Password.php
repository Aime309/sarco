<?php

declare(strict_types=1);

namespace SARCO\Users\Domain;

final readonly class Password {
  function __construct(public string $value) {
  }
}
