<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

use Stringable;

final readonly class Address implements Stringable {
  private const MINIMUM = 3;
  private const PATTERN = '/^[a-zA-Z0-9\s]{3,}$/';
  public string $value;

  /** @throws InvalidAddress */
  function __construct(string $value) {
    self::validate($value);

    $this->value = self::format($value);
  }

  function __toString(): string {
    return $this->value;
  }

  private static function validate(string $value): void {
    $length = mb_strlen($value);

    if ($length < self::MINIMUM || !preg_match(self::PATTERN, $value)) {
      throw new InvalidAddress;
    }
  }

  private static function format(string $value): string {
    return mb_convert_case($value, MB_CASE_TITLE);
  }
}
