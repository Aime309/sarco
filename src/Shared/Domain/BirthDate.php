<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

use DateTimeInterface;

final readonly class BirthDate {
  private const MINIMUM = '1906-01-01';

  function __construct(public DateTimeInterface $value) {
    $this->validate();
  }

  private function validate(): void {
    if ($this->value->format('Y-m-d') < self::MINIMUM) {
      throw new InvalidBirthDate;
    }
  }
}
