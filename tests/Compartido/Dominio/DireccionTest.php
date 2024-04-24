<?php

namespace Tests\Compartido\Dominio;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SARCOV2\Compartido\Dominio\Direccion;
use SARCOV2\Compartido\Dominio\Excepciones\DireccionInvalida;

final class DireccionTest extends TestCase {
  #[Test]
  #[DataProvider('direccionesInvalidas')]
  function lanza_una_excepcion_con_direcciones_invalidas(string $direccion): void {
    self::expectException(DireccionInvalida::class);

    new Direccion($direccion);
  }

  #[Test]
  function puede_formatear_argumentos_validos(): void {
    self::assertSame('El Pinar', (string) new Direccion('el Pinar'));
  }

  static function direccionesInvalidas(): array {
    return [
      [''],
      ['<script>alert("hackeado")</script>']
    ];
  }
}
