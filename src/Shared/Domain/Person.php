<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

use DateTimeImmutable;
use Exception;

abstract class Person {
  private Names $names;
  private LastNames $lastNames;
  private IDCard $idCard;
  protected Gender $gender;
  private BirthDate $birthDate;
  private RegisteredDate $registeredDate;

  /** @throws InvalidArgumentException */
  function __construct(
    string $names,
    string $lastNames,
    int $idCard,
    string $gender,
    string $birthDate,
    string $registeredDate
  ) {
    $this->names = new Names($names);
    $this->lastNames = new LastNames($lastNames);
    $this->idCard = new IDCard($idCard);
    $this->gender = Gender::from($gender);

    try {
      $this->birthDate = new BirthDate(new DateTimeImmutable($birthDate));
    } catch (Exception) {
      throw new InvalidBirthDate;
    }

    try {
      $registeredDateTime = new DateTimeImmutable($registeredDate);
    } catch (Exception) {
      throw new InvalidRegisteredDate;
    }

    $this->registeredDate = new RegisteredDate($registeredDateTime);
  }

  final function names(): string {
    return (string) $this->names;
  }

  final function lastNames(): string {
    return (string) $this->lastNames;
  }

  final function fullName(): string {
    return "$this->names $this->lastNames";
  }

  final function idCard(): int {
    return $this->idCard->value;
  }

  final function birthDate(string $format): string {
    return $this->birthDate->value->format($format);
  }

  function registeredDate(string $format): string {
    return $this->registeredDate->value->format($format);
  }
}
