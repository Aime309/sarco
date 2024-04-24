<?php

namespace Tests\Compartido\Dominio;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SARCOV2\Compartido\Dominio\Correo;
use SARCOV2\Compartido\Dominio\Excepciones\CorreoInvalido;

final class CorreoTest extends TestCase {
  #[Test]
  #[DataProvider('correosInvalidos')]
  function lanza_una_excepcion_con_correos_invalidos(string $correo): void {
    self::expectException(CorreoInvalido::class);

    new Correo($correo);
  }

  #[Test]
  function puede_formatear_argumentos_validos(): void {
    self::assertSame(
      'franyeradriansanchez@gmail.com',
      (string) new Correo('franyeradriansanchez@gmail.com')
    );
  }

  static function correosInvalidos(): array {
    return [
      [''],
      ['<script>alert("hackeado")</script>']
    ];
  }
}
