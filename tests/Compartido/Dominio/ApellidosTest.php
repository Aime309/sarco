<?php

namespace Tests\Compartido\Dominio;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SARCOV2\Compartido\Dominio\Apellidos;
use SARCOV2\Compartido\Dominio\Excepciones\ApellidosInvalidos;

final class ApellidosTest extends TestCase {
  #[Test]
  #[DataProvider('apellidosInvalidos')]
  function lanza_una_excepcion_con_apellidos_invalidos(
    string $primerApellido,
    ?string $segundoApellido = null
  ): void {
    self::expectException(ApellidosInvalidos::class);

    new Apellidos($primerApellido, $segundoApellido);
  }

  #[Test]
  function puede_formatear_argumentos_validos(): void {
    self::assertSame('Sánchez Guillén', (string) new Apellidos('sánchez', 'guillén'));
    self::assertSame('Sánchez', (string) new Apellidos('sánchez'));
  }

  static function apellidosInvalidos(): array {
    return [
      ['123'],
      ['123', '123']
    ];
  }
}
