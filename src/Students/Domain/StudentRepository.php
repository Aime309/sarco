<?php

declare(strict_types=1);

namespace SARCO\Students\Domain;

interface StudentRepository {
  function save(Student $student): void;
}
