<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

use Stringable;

final readonly class IDCard implements Stringable {
  private const MINIMUM = 11_000_000;
  private const MAXIMUM = 31_000_000;

  /** @throws InvalidIDCard */
  function __construct(public int $value) {
    $this->validate();
  }

  function __toString(): string {
    return "$this->value";
  }

  private function validate(): void {
    if ($this->value < self::MINIMUM || $this->value > self::MAXIMUM) {
      throw new InvalidIDCard;
    }
  }
}
