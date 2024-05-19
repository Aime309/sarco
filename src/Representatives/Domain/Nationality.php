<?php

declare(strict_types=1);

namespace SARCO\Representatives\Domain;

use SARCO\Shared\Domain\Gender;

enum Nationality: string {
  case Venezuelan = 'Venezolano';
  case Foreign = 'Extranjero';

  function toString(Gender $gender): string {
    if ($gender->isMale()) {
      return $this->value;
    }

    return match ($this) {
      self::Venezuelan => 'Venezolana',
      self::Foreign => 'Extranjera'
    };
  }
}
