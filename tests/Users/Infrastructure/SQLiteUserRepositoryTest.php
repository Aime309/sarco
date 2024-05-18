<?php

declare(strict_types=1);

namespace SARCO\Tests\Users\Infrastructure;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SARCO\Shared\Domain\DuplicatedEmail;
use SARCO\Shared\Domain\DuplicatedFullName;
use SARCO\Shared\Domain\DuplicatedIDCard;
use SARCO\Shared\Domain\DuplicatedPhone;
use SARCO\Users\Domain\Director;
use SARCO\Users\Domain\Teacher;
use SARCO\Users\Infrastructure\SQLiteUserRepository;
use SQLite3;

final class SQLiteUserRepositoryTest extends TestCase {
  private readonly SQLite3 $connection;
  private readonly SQLiteUserRepository $repository;

  protected function setUp(): void {
    parent::setUp();

    $this->connection = new SQLite3(':memory:');

    $this->connection->query("
      CREATE TABLE usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nombres VARCHAR(40) NOT NULL CHECK (LENGTH(nombres) BETWEEN 3 AND 40),
        apellidos VARCHAR(40) NOT NULL CHECK (LENGTH(apellidos) BETWEEN 3 AND 40),
        cedula INTEGER NOT NULL UNIQUE CHECK (cedula BETWEEN 1000000 AND 99999999),
        fecha_nacimiento DATE NOT NULL CHECK (fecha_nacimiento >= '1906-01-01'),
        direccion TEXT NOT NULL CHECK (LENGTH(direccion) >= 3),
        telefono CHAR(15) NOT NULL UNIQUE CHECK (LENGTH(telefono) = 15 AND telefono LIKE '+__ ___-_______' /* AND telefono REGEXP '^\+\d{2} \d{3}-\d{7}$' */),
        correo VARCHAR(255) NOT NULL UNIQUE CHECK (LENGTH(correo) >= 5 AND correo LIKE '%@%.%'),
        clave TEXT NOT NULL CHECK (LENGTH(clave) >= 8),
        rol VARCHAR(12) NOT NULL CHECK (rol IN ('Director', 'Directora', 'Docente', 'Secretario', 'Secretaria')),
        esta_activo BOOL DEFAULT TRUE,
        fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP CHECK (fecha_registro > '2006-01-01 00:00:00'),

        UNIQUE (nombres, apellidos)
      );
    ");

    $this->repository = new SQLiteUserRepository($this->connection);
  }

  #[Test]
  function can_save_one_director(): void {
    $user = new Director(
      '',
      'Franyer',
      'Sánchez',
      28072391,
      'Masculino',
      '2001-10-06',
      'El Pinar',
      '+584165335826',
      'franyeradriansanchez@gmail.com',
      'Fran.1234',
      true,
      date('Y-m-d H:i:s')
    );

    $this->repository->save($user);

    $ids = $this->connection->querySingle('SELECT COUNT(id) FROM usuarios');
    self::assertSame(1, $ids);
  }

  #[Test]
  function can_save_two_teachers(): void {
    $teacher1 = new Teacher(
      '',
      'Franyer',
      'Sánchez',
      28072391,
      'Masculino',
      '2001-10-06',
      'El Pinar',
      '+584165335826',
      'franyeradriansanchez@gmail.com',
      'Fran.1234',
      true,
      date('Y-m-d H:i:s')
    );

    $teacher2 = new Teacher(
      '',
      'Yender',
      'Sánchez',
      30735099,
      'Masculino',
      '2004-04-30',
      'El Pinar',
      '04161231234',
      'yender@gmail.com',
      'Fran.1234',
      true,
      date('Y-m-d H:i:s')
    );

    $this->repository->save($teacher1);
    $this->repository->save($teacher2);

    $ids = $this->connection->querySingle('SELECT COUNT(id) FROM usuarios');
    self::assertSame(2, $ids);
  }

  #[Test]
  function cannot_save_two_directors_with_same_full_name(): void {
    $user = new Director(
      '',
      'Franyer',
      'Sánchez',
      28072391,
      'Masculino',
      '2001-10-06',
      'El Pinar',
      '+584165335826',
      'franyeradriansanchez@gmail.com',
      'Fran.1234',
      true,
      date('Y-m-d H:i:s')
    );

    $this->repository->save($user);

    self::expectException(DuplicatedFullName::class);
    $this->repository->save($user);

    $ids = $this->connection->querySingle('SELECT COUNT(id) FROM usuarios');
    self::assertSame(1, $ids);
  }

  #[Test]
  function cannot_save_two_directors_with_same_id_card(): void {
    $params = [
      '',
      'Franyer',
      'Sánchez',
      28072391,
      'Masculino',
      '2001-10-06',
      'El Pinar',
      '+584165335826',
      'franyeradriansanchez@gmail.com',
      'Fran.1234',
      true,
      date('Y-m-d H:i:s')
    ];

    $user = new Director(...$params);
    $params[2] = 'Guillén';
    $params[7] = '+584247542450';
    $params[8] = 'franyersanchez06@hotmail.com';
    $user2 = new Director(...$params);

    $this->repository->save($user);

    self::expectException(DuplicatedIDCard::class);
    $this->repository->save($user2);

    $ids = $this->connection->querySingle('SELECT COUNT(id) FROM usuarios');
    self::assertSame(1, $ids);
  }

  #[Test]
  function cannot_save_two_directors_with_same_phone(): void {
    $params = [
      '',
      'Franyer',
      'Sánchez',
      28072391,
      'Masculino',
      '2001-10-06',
      'El Pinar',
      '+584165335826',
      'franyeradriansanchez@gmail.com',
      'Fran.1234',
      true,
      date('Y-m-d H:i:s')
    ];

    $user = new Director(...$params);
    $params[2] = 'Guillén';
    $params[8] = 'franyersanchez06@hotmail.com';
    $user2 = new Director(...$params);

    $this->repository->save($user);

    self::expectException(DuplicatedPhone::class);
    $this->repository->save($user2);

    $ids = $this->connection->querySingle('SELECT COUNT(id) FROM usuarios');
    self::assertSame(1, $ids);
  }

  #[Test]
  function cannot_save_two_directors_with_same_email(): void {
    $params = [
      '',
      'Franyer',
      'Sánchez',
      28072391,
      'Masculino',
      '2001-10-06',
      'El Pinar',
      '+584165335826',
      'franyeradriansanchez@gmail.com',
      'Fran.1234',
      true,
      date('Y-m-d H:i:s')
    ];

    $user = new Director(...$params);
    $params[2] = 'Guillén';
    $user2 = new Director(...$params);

    $this->repository->save($user);

    self::expectException(DuplicatedEmail::class);
    $this->repository->save($user2);

    $ids = $this->connection->querySingle('SELECT COUNT(id) FROM usuarios');
    self::assertSame(1, $ids);
  }
}
