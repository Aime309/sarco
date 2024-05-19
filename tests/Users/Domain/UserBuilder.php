<?php

declare(strict_types=1);

namespace SARCO\Tests\Users\Domain;

use Faker\Factory;
use SARCO\Shared\Domain\Gender;
use SARCO\Users\Domain\User;
use Symfony\Component\Uid\UuidV4;

final class UserBuilder {
  private string $role;
  private ?UuidV4 $id = null;
  private ?string $registeredDate = null;
  private ?string $names = null;
  private ?string $lastNames = null;
  private ?int $idCard = null;
  private ?Gender $gender = null;
  private ?string $birthDate = null;
  private ?string $phone = null;
  private ?string $email = null;
  private ?string $address = null;
  private ?string $password = null;
  private bool $isActive = true;

  private function __construct() {}

  /** @param class-string<User> $role */
  static function withRole(string $role): self {
    $factory = new self;
    $factory->role = $role;

    return $factory;
  }

  function withPhone(string $phone): self {
    $this->phone = $phone;

    return $this;
  }

  function withIdCard(int $idCard): self {
    $this->idCard = $idCard;

    return $this;
  }

  function withEmail(string $email): self {
    $this->email = $email;

    return $this;
  }

  function build(): User {
    $faker = Factory::create();

    return new ($this->role)(
      (string) ($this->id ?? new UuidV4),
      $this->registeredDate ?? date('Y-m-d H:i:s'),
      $this->names ?? rand(0, 1)
        ? $faker->firstName
        : "$faker->firstName $faker->firstName",
      $this->lastNames ?? rand(0, 1)
        ? $faker->lastName
        : "$faker->lastName $faker->lastName",
      $this->idCard ?? rand(11_000_000, 31_000_000),
      ($this->gender ?? $faker->randomElement(Gender::cases()))->value,
      $this->birthDate ?? $faker->dateTimeBetween()->format('Y-m-d'),
      $this->phone ?? '+'
        . rand(10, 99)
        . ' '
        . rand(100, 999)
        . '-'
        . rand(1_000_000, 9_999_999),
      $this->email ?? $faker->email,
      $this->address ?? $faker->address,
      $this->password ?? 'Fran.1234',
      $this->isActive
    );
  }
}
