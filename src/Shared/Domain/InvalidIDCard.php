<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

use InvalidArgumentException;

final class InvalidIDCard extends InvalidArgumentException {
  protected $message = 'Cédula inválida (Debe estar entre 11.000.000 y
  31.000.000)';
}
