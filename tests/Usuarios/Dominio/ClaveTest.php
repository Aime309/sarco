<?php

namespace Tests\Usuarios\Dominio;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SARCOV2\Usuarios\Dominio\Clave;
use SARCOV2\Usuarios\Dominio\Excepciones\ClaveInvalida;

final class ClaveTest extends TestCase {
  #[Test]
  function lanza_una_excepcion_con_una_clave_invalida(): void {
    self::expectException(ClaveInvalida::class);

    Clave::encriptar('');
    Clave::encriptar('<script>alert("hackeado")</script>');
  }

  #[Test]
  function lanza_una_excepcion_al_instanciar_sin_encriptar(): void {
    self::expectException(ClaveInvalida::class);

    new Clave('1234');
  }

  #[Test]
  function se_puede_instanciar_con_argumentos_validos(): void {
    $clave = Clave::encriptar('12345678');

    self::assertNotSame('12345678', $clave);
  }

  #[Test]
  function puede_comprobar_contraseñas(): void {
    $clave = Clave::encriptar('12345678');

    self::assertTrue($clave->esValida('12345678'));
    self::assertFalse($clave->esValida('123456789'));
  }

  #[Test]
  function puede_reinicializar_a_partir_de_clave_encriptada(): void {
    $clave = Clave::encriptar('12345678');
    $mismaClave = new Clave($clave);

    self::assertEquals($clave, $mismaClave);
  }
}
