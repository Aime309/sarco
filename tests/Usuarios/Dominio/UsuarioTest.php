<?php

namespace Tests\Usuarios\Dominio;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SARCOV2\Compartido\Dominio\FechaNacimiento;
use SARCOV2\Compartido\Dominio\Genero;
use SARCOV2\Usuarios\Dominio\Rol;
use SARCOV2\Usuarios\Dominio\Usuario;

final class UsuarioTest extends TestCase {
  #[Test]
  function se_pueden_obtener_todos_los_atributos(): void {
    $usuario = Usuario::instanciar(
      'Franyer Adrián',
      'Sánchez Guillén',
      28072391,
      new FechaNacimiento(2001, 10, 06),
      Genero::Masculino,
      'El Pinar',
      '+584165335826',
      'franyeradriansanchez@gmail.com',
      'fadrian06',
      '12345678',
      Rol::Director
    );

    self::assertSame('Franyer Adrián', $usuario->nombres());
    self::assertSame('Sánchez Guillén', $usuario->apellidos());
    self::assertSame(28072391, $usuario->cedula());
    self::assertSame('2001-10-06', $usuario->fechaNacimiento('Y-m-d'));
    self::assertSame('El Pinar', $usuario->direccion());
    self::assertSame('+58 416 533 5826', $usuario->telefono());
    self::assertSame('franyeradriansanchez@gmail.com', $usuario->correo());
    self::assertSame('fadrian06', $usuario->apodo());
    self::assertSame(Rol::Director, $usuario->rol());
  }
}
