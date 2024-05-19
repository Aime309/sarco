<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

use PharIo\Manifest\Email;

abstract class ContactablePerson extends Person {
  private Phone $phone;
  private Email $email;

  function __construct(
    string $id,
    string $registeredDate,
    string $names,
    string $lastNames,
    int $idCard,
    string $gender,
    string $birthDate,
    string $phone,
    string $email
  ) {
    parent::__construct(
      $id,
      $registeredDate,
      $names,
      $lastNames,
      $idCard,
      $gender,
      $birthDate,
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
