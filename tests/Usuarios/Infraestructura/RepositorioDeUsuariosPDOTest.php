<?php

namespace Tests\Usuarios\Infraestructura;

use PDO;
use PHPUnit\Framework\{Attributes\Test, TestCase};
use SARCOV2\Compartido\Dominio\{FechaNacimiento, Genero};
use SARCOV2\Usuarios\Dominio\{Rol, Usuario};
use SARCOV2\Usuarios\Infraestructura\RepositorioDeUsuariosPDO;

final class RepositorioDeUsuariosPDOTest extends TestCase {
  private RepositorioDeUsuariosPDO $repositorio;

  function setUp(): void {
    $pdo = new PDO(
      'mysql:host=localhost;dbname=sarco2;charset=utf8',
      'root'
    );

    $this->repositorio = new RepositorioDeUsuariosPDO($pdo);
    $pdo->exec('DELETE FROM usuarios WHERE usuario = "fadrian06"');
  }

  #[Test]
  function obtiene_todos_los_usuarios(): void {
    $usuarios = $this->repositorio->obtenerTodos();

    self::assertCount(10, $usuarios);
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

    self::assertCount(10, $this->repositorio->obtenerTodos());

    $this->repositorio->guardar($usuario);
    self::assertCount(11, $this->repositorio->obtenerTodos());
  }
}
