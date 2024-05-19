<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

use DateTimeImmutable;
use Exception;
use InvalidArgumentException;
use Symfony\Component\Uid\UuidV4;

abstract class Model {
  private readonly UuidV4 $id;
  private readonly RegisteredDate $registeredDate;

  /** @throws InvalidArgumentException */
  function __construct(string $id, string $registeredDate) {
    $this->id = new UuidV4($id);

    try {
      $registeredDateTime = new DateTimeImmutable($registeredDate);
    } catch (Exception) {
      throw new InvalidRegisteredDate;
    }

    $this->registeredDate = new RegisteredDate($registeredDateTime);
  }

  final function id(): string {
    return (string) $this->id;
  }

  final function registeredDate(string $format): string {
    return $this->registeredDate->value->format($format);
  }
}
