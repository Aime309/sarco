<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

use InvalidArgumentException;

final class InvalidRegisteredDate extends InvalidArgumentException {
  protected $message = 'La fecha de registro debe ser mayor al 1/1/2006';
}
