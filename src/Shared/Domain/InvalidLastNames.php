<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

final class InvalidLastNames extends InvalidNames {
  protected $message = 'Apellidos inválidos (Deben ser mínimo 1 palabra, 
  máximo 2 palabras)';
}
