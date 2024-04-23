<?php

namespace Tests\Usuarios\Aplicacion;

use PDO;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SARCOV2\Compartido\Dominio\FechaNacimiento;
use SARCOV2\Compartido\Dominio\Genero;
use SARCOV2\Usuarios\Aplicacion\ConsultadorDeUsuarios;
use SARCOV2\Usuarios\Aplicacion\RegistradorDeUsuario;
use SARCOV2\Usuarios\Dominio\Rol;
use SARCOV2\Usuarios\Infraestructura\RepositorioDeUsuariosPDO;

final class RegistradorDeUsuarioTest extends TestCase {
  function setUp(): void {
    $pdo = new PDO(
      'mysql:host=localhost;dbname=sarco2;charset=utf8',
      'root'
    );

    $pdo->exec('DELETE FROM usuarios WHERE usuario = "fadrian06"');
  }

  #[Test]
  function puede_guardar_un_usuario(): void {
    $repositorio = new RepositorioDeUsuariosPDO(new PDO(
      'mysql:host=localhost;dbname=sarco2;charset=utf8',
      'root'
    ));

    $registrador = new RegistradorDeUsuario($repositorio);
    $consultador = new ConsultadorDeUsuarios($repositorio);

    self::assertCount(10, $consultador->obtenerTodos());

    $registrador(
      'Franyer Adrián',
      'Sánchez Guillén',
      28072391,
      new FechaNacimiento(2001, 10, 6),
      Genero::Masculino,
      'El Pinar',
      '+584165335826',
      'franyeradriansanchez@gmail.com',
      'fadrian06',
      '12345678',
      Rol::Director
    );

    self::assertCount(11, $consultador->obtenerTodos());
  }
}
