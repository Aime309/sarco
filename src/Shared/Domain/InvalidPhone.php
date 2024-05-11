<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

use InvalidArgumentException;

final class InvalidPhone extends InvalidArgumentException {
  protected $message = 'Teléfono inválido (Debe tener el formato +xxYYYzzzzzzz
  o xxxxYYYzzzz)';
}
