<?php

declare(strict_types=1);

namespace SARCO\Users\Application;

use InvalidArgumentException;
use SARCO\Users\Domain\Director;
use SARCO\Users\Domain\Secretary;
use SARCO\Users\Domain\Teacher;
use SARCO\Users\Domain\UserCouldNotSave;
use SARCO\Users\Domain\UserRepository;

final readonly class UserRegister {
  function __construct(private UserRepository $repository) {
  }

  /**
   * @throws InvalidArgumentException
   * @throws UserCouldNotSave
   */
  function __invoke(
    string $names,
    string $lastNames,
    int $idCard,
    string $gender,
    string $birthDate,
    string $address,
    string $phone,
    string $email,
    string $password,
    string $role
  ): void {
    $id = '';
    $password = password_hash($password, PASSWORD_DEFAULT);
    $registeredDate = date('Y-m-d H:i:s');
    $isActive = true;

    $params = compact(
      'id',
      'names',
      'lastNames',
      'idCard',
      'gender',
      'birthDate',
      'address',
      'phone',
      'email',
      'password',
      'isActive',
      'registeredDate'
    );

    $user = match (strtolower($role)) {
      'director', 'directora' => new Director(...$params),
      'secretario', 'secretaria' => new Secretary(...$params),
      default => new Teacher(...$params)
    };

    $this->repository->save($user);
  }
}
