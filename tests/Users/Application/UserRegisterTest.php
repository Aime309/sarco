<?php

declare(strict_types=1);

namespace SARCO\Tests\Users\Application;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SARCO\Users\Application\UserRegister;
use SARCO\Users\Domain\UserRepository;

final class UserRegisterTest extends TestCase {
  private readonly UserRepository&MockObject $repository;
  private readonly UserRegister $userRegister;

  protected function setUp(): void {
    parent::setUp();

    try {
      $this->repository = self::createMock(UserRepository::class);
    } catch (Exception) {
    }

    $this->userRegister = new UserRegister($this->repository);
  }

  #[Test]
  function can_save_one_user(): void {
    $this->repository->expects(self::once())->method('save');

    $this->userRegister->__invoke(
      'Franyer',
      'Sánchez',
      28072391,
      'Masculino',
      '2001-10-06',
      'El Pinar',
      '+584165335826',
      'franyeradriansanchez@gmail.com',
      'Fran.1234',
      'Director'
    );
  }
}
