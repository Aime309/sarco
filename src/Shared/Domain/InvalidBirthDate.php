<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

use DomainException;

final class InvalidBirthDate extends DomainException {
  protected $message = 'Fecha de nacimiento inválida (Debe ser mayor al
  1/1/1906)';
}
