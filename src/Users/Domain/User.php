<?php

declare(strict_types=1);

namespace SARCO\Users\Domain;

use DateTimeImmutable;
use SARCO\Shared\Domain\LastNames;
use SARCO\Shared\Domain\Names;

abstract class User {
  public readonly int $id;
  private Names $names;
  private LastNames $lastNames;
  private IDCard $idCard;
  private BirthDate $birthDate;
  private Address $address;
  private Phone $phone;
  private Email $email;
  private Password $password;
  private bool $isActive;
  private DateTimeImmutable $registeredDate;

  function __construct(
    int $id,
    string $names,
    string $lastNames,
    int $idCard,
    string $birthDate,
    string $address,
    string $phone,
    string $password,
    bool $isActive,
    string $registeredDate
  ) {}
}
