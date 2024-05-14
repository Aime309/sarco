<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

use DomainException;

final class InvalidPhone extends DomainException {
  protected $message = 'Teléfono inválido (Debe tener el formato +xxYYYzzzzzzz
  o xxxxYYYzzzz)';
}
