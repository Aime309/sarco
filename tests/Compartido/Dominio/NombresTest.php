<?php

namespace Tests\Compartido\Dominio;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SARCOV2\Compartido\Dominio\Excepciones\NombresInvalidos;
use SARCOV2\Compartido\Dominio\Nombres;

final class NombresTest extends TestCase {
  #[Test]
  function lanza_una_excepcion_con_nombres_invalidos(): void {
    self::expectException(NombresInvalidos::class);

    new Nombres('123');
    new Nombres('123', '123');
  }

  #[Test]
  function puede_formatear_argumentos_validos(): void {
    self::assertSame('Franyer Adrián', (string) new Nombres('franyer', 'adrián'));
    self::assertSame('Franyer', (string) new Nombres('franyer'));
  }
}
