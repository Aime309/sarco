<?php

declare(strict_types=1);

namespace SARCO\Users\Domain;

use DateTimeImmutable;
use DomainException;
use PharIo\Manifest\Email;
use SARCO\Shared\Domain\Address;
use SARCO\Shared\Domain\BirthDate;
use SARCO\Shared\Domain\Gender;
use SARCO\Shared\Domain\IDCard;
use SARCO\Shared\Domain\LastNames;
use SARCO\Shared\Domain\Names;
use SARCO\Shared\Domain\Phone;
use SARCO\Shared\Domain\RegisteredDate;

abstract class User {
  private readonly UserID $id;
  private Names $names;
  private LastNames $lastNames;
  private IDCard $idCard;
  protected Gender $gender;
  private BirthDate $birthDate;
  private Address $address;
  private Phone $phone;
  private Email $email;
  private Password $password;
  private bool $isActive;
  private RegisteredDate $registeredDate;

  /** @throws DomainException */
  function __construct(
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
    $this->id = new UserID($id);
    $this->names = new Names($names);
    $this->lastNames = new LastNames($lastNames);
    $this->idCard = new IDCard($idCard);
    $this->gender = Gender::from($gender);
    $this->birthDate = new BirthDate(new DateTimeImmutable($birthDate));
    $this->address = new Address($address);
    $this->email = new Email($email);
    $this->phone = new Phone($phone);
    $this->password = new Password($password);
    $this->isActive = $isActive;

    $registeredDateTime = new DateTimeImmutable($registeredDate);
    $this->registeredDate = new RegisteredDate($registeredDateTime);
  }

  abstract function role(): string;

  function id(): string {
    return (string) $this->id;
  }

  function names(): string {
    return (string) $this->names;
  }

  function lastNames(): string {
    return (string) $this->lastNames;
  }

  function fullName(): string {
    return "$this->names $this->lastNames";
  }

  function idCard(): int {
    return $this->idCard->value;
  }

  function birthDate(string $format): string {
    return $this->birthDate->value->format($format);
  }

  function address(): string {
    return $this->address->value;
  }

  function phone(): string {
    return $this->phone->value;
  }

  function email(): string {
    return $this->email->asString();
  }

  function password(): string {
    return $this->password->value;
  }

  function isActive(): bool {
    return $this->isActive;
  }

  function registeredDate(string $format): string {
    return $this->registeredDate->value->format($format);
  }
}
