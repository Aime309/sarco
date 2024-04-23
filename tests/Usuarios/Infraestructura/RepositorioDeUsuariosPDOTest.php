<?php

namespace Tests\Usuarios\Infraestructura;

use PDO;
use PHPUnit\Framework\{Attributes\Test, TestCase};
use SARCOV2\Compartido\Dominio\{FechaNacimiento, Genero};
use SARCOV2\Compartido\Dominio\Excepciones\{
  CedulaDuplicada,
  CorreoDuplicado,
  NombreCompletoDuplicado,
  TelefonoDuplicado
};
use SARCOV2\Usuarios\Dominio\{Rol, Usuario};
use SARCOV2\Usuarios\Dominio\Excepciones\UsuarioDuplicado;
use SARCOV2\Usuarios\Infraestructura\RepositorioDeUsuariosPDO;

final class RepositorioDeUsuariosPDOTest extends TestCase {
  private RepositorioDeUsuariosPDO $repositorio;

  function setUp(): void {
    $pdo = new PDO('sqlite::memory:');
    $this->repositorio = new RepositorioDeUsuariosPDO($pdo);

    $pdo->exec('
      CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nombres VARCHAR(40) NOT NULL CHECK (LENGTH(nombres) BETWEEN 3 AND 40),
        apellidos VARCHAR(40) NOT NULL CHECK (LENGTH(apellidos) BETWEEN 3 AND 40),
        cedula INTEGER NOT NULL UNIQUE CHECK (cedula BETWEEN 1000000 AND 99999999),
        fecha_nacimiento DATE NOT NULL CHECK (fecha_nacimiento >= "1906-01-01"),
        direccion TEXT NOT NULL CHECK (LENGTH(direccion) >= 3),
        telefono CHAR(16) NOT NULL UNIQUE CHECK (LENGTH(telefono) = 16 AND telefono LIKE "+__ ___ ___ ____" /* AND telefono REGEXP "^\+\d{2} \d{3} \d{3} \d{4}$" */),
        correo VARCHAR(255) NOT NULL UNIQUE CHECK (LENGTH(correo) >= 5 AND correo LIKE "%@%.%"),
        usuario VARCHAR(20) NOT NULL UNIQUE CHECK (LENGTH(usuario) BETWEEN 3 AND 20),
        clave TEXT NOT NULL CHECK (LENGTH(clave) >= 8),
        rol VARCHAR(12) NOT NULL CHECK (rol IN ("Director/a", "Docente", "Secretario/a")),
        esta_activo BOOL DEFAULT TRUE,
        fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP CHECK (fecha_registro > "2006-01-01 00:00:00"),

        UNIQUE (nombres, apellidos)
      )
    ');

    $pdo->exec('DELETE FROM usuarios');
  }

  #[Test]
  function devuelve_vacio_si_no_hay_usuarios(): void {
    $usuarios = $this->repositorio->obtenerTodos();

    self::assertCount(0, $usuarios);
  }

  #[Test]
  function guarda_un_usuario(): void {
    $usuario = Usuario::instanciar(
      'Franyer Adrián',
      'Sánchez Guillén',
      28072391,
      new FechaNacimiento(2001, 10, 06),
      Genero::Masculino,
      'El Pinar',
      '+58 416 533 5826',
      'franyeradriansanchez@gmail.com',
      'fadrian06',
      '12345678',
      Rol::Director
    );

    $this->repositorio->guardar($usuario);
    self::assertCount(1, $this->repositorio->obtenerTodos());
  }

  #[Test]
  function no_puede_guardar_dos_usuarios_con_los_mismos_nombres_y_apellidos(): void {
    $usuario = Usuario::instanciar(
      'Franyer Adrián',
      'Sánchez Guillén',
      28072391,
      new FechaNacimiento(2001, 10, 06),
      Genero::Masculino,
      'El Pinar',
      '+58 416 533 5826',
      'franyeradriansanchez@gmail.com',
      'fadrian06',
      '12345678',
      Rol::Director
    );

    $this->repositorio->guardar($usuario);

    self::expectException(NombreCompletoDuplicado::class);
    $this->repositorio->guardar($usuario);
  }

  #[Test]
  function no_puede_guardar_dos_usuarios_con_el_mismo_usuario(): void {
    $usuario = Usuario::instanciar(
      'Franyer',
      'Sánchez',
      28072391,
      new FechaNacimiento(2001, 10, 06),
      Genero::Masculino,
      'El Pinar',
      '+58 416 533 5826',
      'franyeradriansanchez@gmail.com',
      'fadrian06',
      '12345678',
      Rol::Director
    );

    $usuario2 = Usuario::instanciar(
      'Adrián',
      'Guillén',
      28072391,
      new FechaNacimiento(2001, 10, 06),
      Genero::Masculino,
      'El Pinar',
      '+58 416 533 5826',
      'franyeradriansanchez@gmail.com',
      'fadrian06',
      '12345678',
      Rol::Director
    );

    $this->repositorio->guardar($usuario);

    self::expectException(UsuarioDuplicado::class);
    $this->repositorio->guardar($usuario2);
  }

  #[Test]
  function no_puede_guardar_dos_usuarios_con_el_mismo_correo(): void {
    $usuario = Usuario::instanciar(
      'Franyer',
      'Sánchez',
      28072391,
      new FechaNacimiento(2001, 10, 06),
      Genero::Masculino,
      'El Pinar',
      '+58 416 533 5826',
      'franyeradriansanchez@gmail.com',
      'fadrian06',
      '12345678',
      Rol::Director
    );

    $usuario2 = Usuario::instanciar(
      'Adrián',
      'Guillén',
      28072391,
      new FechaNacimiento(2001, 10, 06),
      Genero::Masculino,
      'El Pinar',
      '+58 416 533 5826',
      'franyeradriansanchez@gmail.com',
      'fran.0610',
      '12345678',
      Rol::Director
    );

    $this->repositorio->guardar($usuario);

    self::expectException(CorreoDuplicado::class);
    $this->repositorio->guardar($usuario2);
  }

  #[Test]
  function no_puede_guardar_dos_usuarios_con_el_mismo_telefono(): void {
    $usuario = Usuario::instanciar(
      'Franyer',
      'Sánchez',
      28072391,
      new FechaNacimiento(2001, 10, 06),
      Genero::Masculino,
      'El Pinar',
      '+58 416 533 5826',
      'franyeradriansanchez@gmail.com',
      'fadrian06',
      '12345678',
      Rol::Director
    );

    $usuario2 = Usuario::instanciar(
      'Yender',
      'Sánchez',
      30735099,
      new FechaNacimiento(2001, 10, 06),
      Genero::Masculino,
      'El Pinar',
      '+58 416 533 5826',
      'franyersanchez06@hotmail.com',
      'fran.0610',
      '12345678',
      Rol::Director
    );

    $this->repositorio->guardar($usuario);

    self::expectException(TelefonoDuplicado::class);
    $this->repositorio->guardar($usuario2);
  }

  #[Test]
  function no_puede_guardar_dos_usuarios_con_la_misma_cedula(): void {
    $usuario = Usuario::instanciar(
      'Franyer',
      'Sánchez',
      28072391,
      new FechaNacimiento(2001, 10, 06),
      Genero::Masculino,
      'El Pinar',
      '+58 416 533 5826',
      'franyeradriansanchez@gmail.com',
      'fadrian06',
      '12345678',
      Rol::Director
    );

    $usuario2 = Usuario::instanciar(
      'Adrián',
      'Guillén',
      28072391,
      new FechaNacimiento(2001, 10, 06),
      Genero::Masculino,
      'El Pinar',
      '+58 424 754 2450',
      'franyersanchez06@hotmail.com',
      'fran.0610',
      '12345678',
      Rol::Director
    );

    $this->repositorio->guardar($usuario);

    self::expectException(CedulaDuplicada::class);
    $this->repositorio->guardar($usuario2);
  }
}
