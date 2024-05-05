<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

use Stringable;

readonly class Names implements Stringable {
  public string $first;
  public ?string $second;
  protected const EXCEPTION = InvalidNames::class;

  function __construct(string $value) {
    static::validate($value);

    [$this->first, $this->second] = self::format($value);
  }

  function __toString(): string {
    $names = $this->first;

    if ($this->second) {
      $names .= " $this->second";
    }

    return $names;
  }

  private static function validate(string $value): void {
    $characters = 'a-zA-ZáéíóúñÁÉÍÓÚÑ';
    $pattern = "/^[$characters]{3,20}(\s[$characters]{3,20})?$/";
    $length = mb_strlen($value);

    if ($length < 3 || $length > 40 || !preg_match($pattern, $value)) {
      throw new (static::EXCEPTION);
    }
  }

  private static function format(string $value): array {
    $value = mb_convert_case($value, MB_CASE_TITLE);
    $value = explode(' ', $value);

    return [$value[0], $value[1] ?? null];
  }
}
