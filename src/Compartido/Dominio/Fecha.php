<?php

namespace SARCOV2\Compartido\Dominio;

use DateTimeImmutable;

readonly class Fecha {
  public int $año;
  public int $mes;
  public int $dia;

  function __construct(int $año, int $mes, int $dia) {
    static::asegurarValidez($año, $mes, $dia);
    $this->inicializar($año, $mes, $dia);
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

  protected static function asegurarValidez(int $año, int $mes, int $dia): void {
    // TODO: validar parámetros
  }
}
