<?php

declare(strict_types=1);

namespace SARCO\Users\Domain;

final class Teacher extends User {
  function role(): string {
    return 'Docente';
  }
}
