<?php

declare(strict_types=1);

namespace SARCO\Tests\Users\Application;

use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SARCO\Users\Application\UserRegister;
use SARCO\Users\Domain\UserRepository;
use Symfony\Component\Uid\UuidV4;

final class UserRegisterTest extends TestCase {
  private readonly UserRepository&MockObject $repository;
  private readonly UserRegister $userRegister;
  private readonly Generator $faker;

  protected function setUp(): void {
    parent::setUp();

    try {
      $this->repository = self::createMock(UserRepository::class);
    } catch (Exception) {
    }

    $this->userRegister = new UserRegister($this->repository);
    $this->faker = Factory::create();
  }

  #[Test]
  function can_save_one_user(): void {
    $this->repository->expects(self::once())->method('save');

    $this->userRegister->__invoke(
      (string) new UuidV4,
      $this->faker->firstName,
      $this->faker->lastName,
      rand(11_000_000, 31_000_000),
      $this->faker->randomElement(['Masculino', 'Femenino']),
      $this->faker->dateTimeBetween()->format('Y-m-d'),
      $this->faker->address,
      '+584165335826',
      $this->faker->email,
      $this->faker->password,
      $this->faker->randomElement(['Director', 'Secretario', 'Docente'])
    );
  }
}
