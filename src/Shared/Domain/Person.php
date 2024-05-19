<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

use DateTimeImmutable;
use Exception;

abstract class Person extends Model {
  private Names $names;
  private LastNames $lastNames;
  private IDCard $idCard;
  protected Gender $gender;
  private BirthDate $birthDate;

  function __construct(
    string $id,
    string $registeredDate,
    string $names,
    string $lastNames,
    int $idCard,
    string $gender,
    string $birthDate,
  ) {
    parent::__construct($id, $registeredDate);

    $this->names = new Names($names);
    $this->lastNames = new LastNames($lastNames);
    $this->idCard = new IDCard($idCard);
    $this->gender = Gender::from($gender);

    try {
      $this->birthDate = new BirthDate(new DateTimeImmutable($birthDate));
    } catch (Exception) {
      throw new InvalidBirthDate;
    }
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

  final function gender(): string {
    return $this->gender->value;
  }
}
