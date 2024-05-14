<?php

declare(strict_types=1);

namespace SARCO\Users\Domain;

use SARCO\Shared\Domain\Gender;

final class Secretary extends User {
  function role(): string {
    return $this->gender === Gender::Male ? 'Secretario' : 'Secretaria';
  }
}
