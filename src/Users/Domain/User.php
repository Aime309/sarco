<?php

declare(strict_types=1);

namespace SARCO\Users\Domain;

use SARCO\Shared\Domain\Address;
use SARCO\Shared\Domain\ContactablePerson;

abstract class User extends ContactablePerson {
  private readonly UserID $id;
  private Address $address;
  private Password $password;
  private bool $isActive;

  final function __construct(
    string $id,
    string $names,
    string $lastNames,
    int $idCard,
    string $gender,
    string $birthDate,
    string $address,
    string $phone,
    string $email,
    string $password,
    bool $isActive,
    string $registeredDate
  ) {
    parent::__construct(
      $names,
      $lastNames,
      $idCard,
      $gender,
      $birthDate,
      $phone,
      $email,
      $registeredDate
    );

    $this->id = new UserID($id);
    $this->address = new Address($address);
    $this->password = new Password($password);
    $this->isActive = $isActive;
  }

  abstract function role(): string;

  function id(): string {
    return (string) $this->id;
  }

  function address(): string {
    return $this->address->value;
  }

  function password(): string {
    return $this->password->value;
  }

  function isActive(): bool {
    return $this->isActive;
  }
}
