<?php

namespace Tests\Compartido\Dominio;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SARCOV2\Compartido\Dominio\Cedula;
use SARCOV2\Compartido\Dominio\Excepciones\CedulaInvalida;

final class CedulaTest extends TestCase {
  #[Test]
  #[DataProvider('cedulasInvalidas')]
  function lanza_una_excepcion_con_cedula_invalida(int $cedula): void {
    self::expectException(CedulaInvalida::class);

    new Cedula($cedula);
  }

  #[Test]
  function se_puede_instanciar_con_argumentos_validos(): void {
    self::assertSame('28072391', (string) new Cedula(28072391));
  }

  static function cedulasInvalidas(): array {
    return [
      [123],
      [100_000_000]
    ];
  }
}
