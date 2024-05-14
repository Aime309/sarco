<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

use DomainException;

final class InvalidAddress extends DomainException {
  protected $message = 'Dirección inválida (Debe tener mínimo 3 caracteres,
  números)';
}
