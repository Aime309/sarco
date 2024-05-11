<?php

declare(strict_types=1);

namespace SARCO\Users\Domain;

use PharIo\Manifest\Email;
use SARCO\Shared\Domain\Address;
use SARCO\Shared\Domain\BirthDate;
use SARCO\Shared\Domain\IDCard;
use SARCO\Shared\Domain\InvalidAddress;
use SARCO\Shared\Domain\InvalidBirthDate;
use SARCO\Shared\Domain\InvalidIDCard;
use SARCO\Shared\Domain\InvalidLastNames;
use SARCO\Shared\Domain\InvalidNames;
use SARCO\Shared\Domain\InvalidPhone;
use SARCO\Shared\Domain\InvalidRegisteredDate;
use SARCO\Shared\Domain\LastNames;
use SARCO\Shared\Domain\Names;
use SARCO\Shared\Domain\Phone;
use SARCO\Shared\Domain\RegisteredDate;

abstract class User {
  private readonly UserID $id;
  private Names $names;
  private LastNames $lastNames;
  private IDCard $idCard;
  private BirthDate $birthDate;
  private Address $address;
  private Phone $phone;
  private Email $email;
  private Password $password;
  private bool $isActive;
  private RegisteredDate $registeredDate;

  /**
   * @throws InvalidNames
   * @throws InvalidLastNames
   * @throws InvalidIDCard
   * @throws InvalidBirthDate
   * @throws InvalidAddress
   * @throws InvalidPhone
   * @throws InvalidRegisteredDate
   */
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
  ) {
  }
}
