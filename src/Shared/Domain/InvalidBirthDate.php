<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

use InvalidArgumentException;

final class InvalidBirthDate extends InvalidArgumentException {
  protected $message = 'Fecha de nacimiento inválida (Debe ser mayor al
  1/1/1906)';
}
