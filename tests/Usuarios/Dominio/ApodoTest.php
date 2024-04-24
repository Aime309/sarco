<?php

namespace Tests\Usuarios\Dominio;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SARCOV2\Usuarios\Dominio\Apodo;
use SARCOV2\Usuarios\Dominio\Excepciones\UsuarioInvalido;

final class ApodoTest extends TestCase {
  #[Test]
  function lanza_una_excepcion_con_un_apodo_invalido(): void {
    self::expectException(UsuarioInvalido::class);

    new Apodo('');
    new Apodo('<script>alert("hackeado")</script>');
  }

  #[Test]
  function se_puede_instanciar_con_argumentos_validos(): void {
    self::assertSame('fadrian06', (string) new Apodo('fadrian06'));
  }
}
