<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

use InvalidArgumentException;

class InvalidNames extends InvalidArgumentException {
  protected $message = 'Nombres inválidos (Deben ser mínimo 1 palabra, 
  máximo 2 palabras)';
}
