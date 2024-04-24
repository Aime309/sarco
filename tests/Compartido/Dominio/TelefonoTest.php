<?php

namespace Tests\Compartido\Dominio;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SARCOV2\Compartido\Dominio\Excepciones\TelefonoInvalido;
use SARCOV2\Compartido\Dominio\Telefono;

final class TelefonoTest extends TestCase {
  #[Test]
  #[DataProvider('telefonoInvalidos')]
  function lanza_una_excepcion_con_telefonos_invalidos(string $telefono): void {
    self::expectException(TelefonoInvalido::class);

    new Telefono($telefono);
  }

  #[Test]
  function puede_formatear_argumentos_validos(): void {
    self::assertSame('+58 416 533 5826', (string) new Telefono('+584165335826'));
  }

  static function telefonoInvalidos(): array {
    return [
      [''],
      ['<script>alert("hackeado")</script>']
    ];
  }
}
