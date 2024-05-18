<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

use DateTimeInterface;

final class RegisteredDate {
  private const MINIMUM = '2006-01-01';

  /** @throws InvalidRegisteredDate */
  function __construct(public DateTimeInterface $value) {
    $this->validate();
  }

  private function validate(): void {
    if ($this->value->format('Y-m-d') < self::MINIMUM) {
      throw new InvalidRegisteredDate;
    }
  }
}
