<?php

declare(strict_types=1);

namespace SARCO\Representatives\Domain;

use SARCO\Shared\Domain\Gender;

enum CivilStatus: string {
  case Married = 'Casado';
  case Single = 'Soltero';
  case Divorced = 'Divorciado';
  case Widower = 'Viudo';

  function toString(Gender $gender): string {
    if ($gender->isMale()) {
      return $this->value;
    }

    return match ($this) {
      self::Divorced => 'Divorciada',
      self::Married => 'Casada',
      self::Single => 'Soltera',
      self::Widower => 'Viuda'
    };
  }
}
