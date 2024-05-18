<?php

declare(strict_types=1);

namespace SARCO\Users\Infrastructure;

use SARCO\Shared\Domain\DuplicatedEmail;
use SARCO\Shared\Domain\DuplicatedFullName;
use SARCO\Shared\Domain\DuplicatedIDCard;
use SARCO\Shared\Domain\DuplicatedPhone;
use SARCO\Users\Domain\User;
use SARCO\Users\Domain\UserRepository;
use SQLite3;

final readonly class SQLiteUserRepository implements UserRepository {
  function __construct(private SQLite3 $connection) {
  }

  function save(User $user): void {
    $wasRegistered = @$this->connection->query("
      INSERT INTO usuarios (nombres, apellidos, cedula, fecha_nacimiento,
      direccion, telefono, correo, clave, rol, esta_activo, fecha_registro)
      VALUES ('{$user->names()}', '{$user->lastNames()}', {$user->idCard()},
      '{$user->birthDate('Y-m-d')}', '{$user->address()}', '{$user->phone()}',
      '{$user->email()}', '{$user->password()}', '{$user->role()}',
      {$user->isActive()}, '{$user->registeredDate('Y-m-d H:i:s')}')
    ");

    if (!$wasRegistered) {
      self::throwError($this->connection->lastErrorMsg(), $user);
    }
  }

  private static function throwError(string $error, User $user): never {
    throw (match (true) {
      str_contains($error, 'usuarios.nombres') => new DuplicatedFullName(
        "Usuario {$user->fullName()} ya existe"
      ),
      str_contains($error, 'usuarios.cedula') => new DuplicatedIDCard(
        "Usuario {$user->idCard()} ya existe"
      ),
      str_contains($error, 'usuarios.telefono') => new DuplicatedPhone(
        $user->phone()
      ),
      str_contains($error, 'usuarios.correo') => new DuplicatedEmail(
        $user->email()
      )
    });
  }
}
