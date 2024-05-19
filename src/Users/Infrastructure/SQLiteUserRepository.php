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
    $stmt = $this->connection->prepare("
      INSERT INTO usuarios (id, nombres, apellidos, cedula, fecha_nacimiento,
      direccion, telefono, correo, clave, rol, esta_activo, fecha_registro)
      VALUES (:id, :names, :lastNames, :idCard, :birthDate, :address, :phone,
      :email, :password, :role, :isActive, :registeredDate)
    ");

    $stmt->bindValue(':id', $user->id());
    $stmt->bindValue(':names', $user->names());
    $stmt->bindValue(':lastNames', $user->lastNames());
    $stmt->bindValue(':idCard', $user->idCard());
    $stmt->bindValue(':birthDate', $user->birthDate('Y-m-d'));
    $stmt->bindValue(':address', $user->address());
    $stmt->bindValue(':phone', $user->phone());
    $stmt->bindValue(':email', $user->email());
    $stmt->bindValue(':password', $user->password());
    $stmt->bindValue(':role', $user->role());
    $stmt->bindValue(':isActive', $user->isActive());
    $stmt->bindValue(':registeredDate', $user->registeredDate('Y-m-d H:i:s'));

    $wasRegistered = @$stmt->execute();

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
      ),
      default => exit(print_r(compact('error', 'user')))
    });
  }
}
