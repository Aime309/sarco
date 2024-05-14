<?php

declare(strict_types=1);

namespace SARCO\Users\Domain;

use RuntimeException;

interface UserRepository {
  /** @throws RuntimeException */
  function save(User $user): void;
}
