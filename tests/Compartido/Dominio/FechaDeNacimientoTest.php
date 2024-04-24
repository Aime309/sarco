<?php

namespace Tests\Compartido\Dominio;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SARCOV2\Compartido\Dominio\Excepciones\FechaInvalida;
use SARCOV2\Compartido\Dominio\FechaNacimiento;

final class FechaDeNacimientoTest extends TestCase {
  #[Test]
  #[DataProvider('fechasDeNacimientoInvalidas')]
  function lanza_una_excepcion_con_fechas_de_nacimiento_invalidas(
    int $año,
    int $mes,
    int $dia
  ): void {
    self::expectException(FechaInvalida::class);

    new FechaNacimiento($año, $mes, $dia);
  }

  #[Test]
  function puede_formatear_argumentos_validos(): void {
    self::assertSame('1906-01-01', (new FechaNacimiento(1906, 1, 1))->formatear('Y-m-d'));
    self::assertSame('06/10/2001', (new FechaNacimiento(2001, 10, 06))->formatear('d/m/Y'));
  }

  static function fechasDeNacimientoInvalidas(): array {
    return [
      [1905, 12, 31],
      [1906, 0, 0]
    ];
  }
}
