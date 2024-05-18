<?php

declare(strict_types=1);

namespace SARCO\Users\Domain;

use InvalidArgumentException;

final class InvalidPassword extends InvalidArgumentException {
  protected $message = 'Contraseña inválida (Debe tener 8 caracteres, mínimo 1
  mayúscula, 1 número y 1 símbolo)';
}
