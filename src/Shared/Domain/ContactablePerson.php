<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

use PharIo\Manifest\Email;

abstract class ContactablePerson extends Person {
  private Phone $phone;
  private Email $email;

  /** @throws InvalidArgumentException */
  function __construct(
    string $names,
    string $lastNames,
    int $idCard,
    string $gender,
    string $birthDate,
    string $phone,
    string $email,
    string $registeredDate
  ) {
    parent::__construct(
      $names,
      $lastNames,
      $idCard,
      $gender,
      $birthDate,
      $registeredDate
    );

    $this->email = new Email($email);
    $this->phone = new Phone($phone);
  }

  final function phone(): string {
    return $this->phone->value;
  }

  final function email(): string {
    return $this->email->asString();
  }
}
