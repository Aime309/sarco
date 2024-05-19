<?php

declare(strict_types=1);

namespace SARCO\Periods\Domain;

use SARCO\Moments\Domain\Moment;

final class Period {
  function __construct(
    private readonly int $id,
    private readonly int $startYear,
    string $registeredDate,
    private readonly Moment $moment1,
    private readonly Moment $moment2,
    private readonly Moment $moment3
  ) {}
}
