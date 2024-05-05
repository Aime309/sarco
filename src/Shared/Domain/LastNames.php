<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

final readonly class LastNames extends Names {
  protected const EXCEPTION = InvalidLastNames::class;
}
