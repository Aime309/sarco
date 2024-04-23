<?php

namespace Tests\Compartido\Dominio;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SARCOV2\Compartido\Dominio\FechaHora;

final class FechaHoraTest extends TestCase {
  #[Test]
  function se_puede_instanciar_de_formato_sql_datetime(): void {
    $fechaHora = FechaHora::instanciar('Y-m-d H:i:s', '2024-04-07 13:34:55');

    self::assertSame(2024, $fechaHora->año);
    self::assertSame(4, $fechaHora->mes);
    self::assertSame(7, $fechaHora->dia);
    self::assertSame(13, $fechaHora->horas);
    self::assertSame(34, $fechaHora->minutos);
    self::assertSame(55, $fechaHora->segundos);
  }
}
