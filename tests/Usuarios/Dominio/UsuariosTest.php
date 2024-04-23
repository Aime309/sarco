<?php

namespace Tests\Usuarios\Dominio;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SARCOV2\Compartido\Dominio\Apellidos;
use SARCOV2\Compartido\Dominio\Cedula;
use SARCOV2\Compartido\Dominio\Correo;
use SARCOV2\Compartido\Dominio\Direccion;
use SARCOV2\Compartido\Dominio\FechaHora;
use SARCOV2\Compartido\Dominio\FechaNacimiento;
use SARCOV2\Compartido\Dominio\Genero;
use SARCOV2\Compartido\Dominio\ID;
use SARCOV2\Compartido\Dominio\Nombres;
use SARCOV2\Compartido\Dominio\Telefono;
use SARCOV2\Usuarios\Dominio\Apodo;
use SARCOV2\Usuarios\Dominio\Clave;
use SARCOV2\Usuarios\Dominio\Rol;
use SARCOV2\Usuarios\Dominio\Usuario;
use SARCOV2\Usuarios\Dominio\Usuarios;

final class UsuariosTest extends TestCase {
  #[Test]
  function devuelve_falso_si_no_hay_directores_activos(): void {
    $coleccion = new Usuarios(new Usuario(
      new ID(''),
      new Nombres('Franyer'),
      new Apellidos('Sánchez'),
      new Cedula(28072391),
      new FechaNacimiento(2001, 10, 6),
      Genero::Masculino,
      new Direccion('El Pinar'),
      new Telefono('+584165335826'),
      new Correo('franyeradriansanchez@gmail.com'),
      new Apodo('fadrian06'),
      Clave::encriptar('12345678'),
      Rol::Director,
      FechaHora::actual(),
      false
    ));

    self::assertFalse($coleccion->hayActivos());

    $coleccion->añadir(Usuario::instanciar(
      'Franyer',
      'Sánchez',
      28072391,
      new FechaNacimiento(2001, 10, 6),
      Genero::Masculino,
      'El Pinar',
      '+584165335826',
      'franyeradriansanchez@gmail.com',
      'fadrian06',
      '12345678',
      Rol::Director
    ));

    self::assertTrue($coleccion->hayActivos());
  }
}
