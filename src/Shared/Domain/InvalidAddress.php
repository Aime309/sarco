<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

use InvalidArgumentException;

final class InvalidAddress extends InvalidArgumentException {
  protected $message = 'Dirección inválida (Debe tener mínimo 3 caracteres,
  números)';
}
