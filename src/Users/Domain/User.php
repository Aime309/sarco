<?php

declare(strict_types=1);

namespace SARCO\Users\Domain;

use SARCO\Shared\Domain\Address;
use SARCO\Shared\Domain\ContactablePerson;

abstract class User extends ContactablePerson {
  private Address $address;
  private Password $password;
  private bool $isActive = true;

  final function __construct(
    string $id,
    string $registeredDate,
    string $names,
    string $lastNames,
    int $idCard,
    string $gender,
    string $birthDate,
    string $phone,
    string $email,
    string $address,
    string $password,
    bool $isActive = true
  ) {
    parent::__construct(
      $id,
      $registeredDate,
      $names,
      $lastNames,
      $idCard,
      $gender,
      $birthDate,
      $phone,
      $email
    );

    $this->address = new Address($address);
    $this->password = new Password($password);
    $this->isActive = $isActive;
  }

  abstract function role(): string;

  final function address(): string {
    return $this->address->value;
  }

  final function password(): string {
    return $this->password->value;
  }

  final function isActive(): bool {
    return $this->isActive;
  }
}
