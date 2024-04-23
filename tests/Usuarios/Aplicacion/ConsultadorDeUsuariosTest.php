<?php

namespace Tests\Usuarios\Aplicacion;

use PDO;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SARCOV2\Usuarios\Aplicacion\ConsultadorDeUsuarios;
use SARCOV2\Usuarios\Infraestructura\RepositorioDeUsuariosPDO;

final class ConsultadorDeUsuariosTest extends TestCase {
  #[Test]
  function puede_consultar_usuarios(): void {
    $consultador = new ConsultadorDeUsuarios(new RepositorioDeUsuariosPDO(new PDO(
      'mysql:host=localhost;dbname=sarco2;charset=utf8',
      'root'
    )));

    self::assertCount(10, $consultador->obtenerTodos());
  }
}
