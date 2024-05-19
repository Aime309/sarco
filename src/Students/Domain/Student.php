<?php

declare(strict_types=1);

namespace SARCO\Students\Domain;

use SARCO\Representatives\Domain\Representative;
use SARCO\Rooms\Domain\Room;
use SARCO\Shared\Domain\Address;
use SARCO\Shared\Domain\Person;

final class Student extends Person {
  private Address $birthPlace;
  private readonly BloodType $bloodType;

  function __construct(
    private readonly int $id,
    string $names,
    string $lastNames,
    int $idCard,
    string $gender,
    string $birthDate,
    string $birthPlace,
    string $registeredDate,
    string $bloodType,
    private readonly Room $room,
    private readonly Representative $mom,
    private readonly ?Representative $dad
  ) {
    parent::__construct(
      $names,
      $lastNames,
      $idCard,
      $gender,
      $birthDate,
      $registeredDate
    );

    $this->birthPlace = new Address($birthPlace);
    $this->bloodType = BloodType::from($bloodType);
  }

  function birthPlace(): string {
    return (string) $this->birthPlace;
  }

  function bloodType(): string {
    return $this->bloodType->value;
  }
}
