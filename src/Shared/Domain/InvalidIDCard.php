<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

use DomainException;

final class InvalidIDCard extends DomainException {
  protected $message = 'Cédula inválida (Debe estar entre 11.000.000 y
  31.000.000)';
}
