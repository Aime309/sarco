<?php

namespace SARCOV2\Compartido\Dominio;

use DateTimeImmutable;
use SARCOV2\Compartido\Dominio\Excepciones\FechaInvalida;

readonly class Fecha {
  public int $año;
  public int $mes;
  public int $dia;

  function __construct(int $año, int $mes, int $dia) {
    $this->inicializar($año, $mes, $dia);
    $this->asegurarValidez($año, $mes, $dia);
  }

  function formatear(string $formato = 'Y-m-d'): string {
    $fecha = DateTimeImmutable::createFromFormat(
      'Y-m-d',
      "$this->año-$this->mes-$this->dia"
    );

    return $fecha->format($formato);
  }

  static function instanciar(string $formato, string $fecha): static {
    $fecha = DateTimeImmutable::createFromFormat($formato, $fecha);

    return new static($fecha->format('Y'), $fecha->format('m'), $fecha->format('d'));
  }

  protected function inicializar(int $año, int $mes, int $dia): void {
    $this->año = $año;
    $this->mes = $mes;
    $this->dia = $dia;
  }

  protected function asegurarValidez(int $año, int $mes, int $dia): void {
    $fecha = DateTimeImmutable::createFromFormat('Y-m-d', "$año-$mes-$dia");

    if (
      $fecha->format('Y') != $año
      || $fecha->format('m') != $mes
      || $fecha->format('j') != $dia
    ) {
      throw new FechaInvalida($fecha->format('Y-m-d'));
    }
  }
}
